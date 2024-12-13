<?php

namespace App\Http\Controllers;

use App\Models\ProcessoCompra;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProcessoCompraController extends Controller
{
    public function index()
    {
        $processos = ProcessoCompra::all();

        // Garantir que a data seja um objeto Carbon
        $processos->each(function ($processo) {
            $processo->data_vigente = \Carbon\Carbon::parse($processo->data_vigente);
        });

        return view('processos.index', compact('processos'));
    }

    public function create()
    {
        // Retorna a view para criar um novo processo de compra
        return view('processos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_processo' => 'required|string|max:255',
            'descricao' => 'required|string|max:1000',
            'data_vigente' => 'required|date',
        ]);

        // Lógica para definir o status baseado na data vigente
        $data_vigente = Carbon::parse($request->data_vigente);
        $hoje = Carbon::today();

        if ($data_vigente->isToday() || $data_vigente->isFuture()) {
            // Se a data vigente for hoje ou no futuro, o status será verde
            $status = 'verde';
        } elseif ($data_vigente->diffInDays($hoje) <= 7) {
            // Se a data vigente estiver a 7 dias ou menos do hoje, o status será amarelo
            $status = 'amarelo';
        } else {
            // Caso contrário, o status será vermelho
            $status = 'vermelho';
        }

        // Cria o novo processo
        ProcessoCompra::create([
            'numero' => $request->numero,
            'descricao' => $request->descricao,
            'data_vigente' => $request->data_vigente,
            'status' => $status, // Atribui o status calculado
        ]);

        // Redireciona para a página de processos com uma mensagem de sucesso
        return redirect()->route('processos.index')->with('success', 'Processo criado com sucesso!');
    }
    
    /**
     * Método para calcular o status com base na data vigente.
     */
    private function calcularStatus($data_vigente, $hoje)
    {
        // Se a data vigente for hoje ou no futuro
        if ($data_vigente->isToday() || $data_vigente->isFuture()) {
            return 'verde'; // Status Verde
        }

        // Se a data vigente está a 7 dias ou menos de diferença do hoje
        if ($data_vigente->diffInDays($hoje) <= 7) {
            return 'amarelo'; // Status Amarelo
        }

        // Caso contrário, o status será vermelho
        return 'vermelho'; // Status Vermelho
    }

}

