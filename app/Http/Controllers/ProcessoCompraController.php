<?php

namespace App\Http\Controllers;

use App\Models\ProcessoCompra;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProcessoCompraController extends Controller
{
    public function index()
    {
        $processos = ProcessoCompra::orderBy('data_vigente', 'asc')->get();

        // Calcular status de cada processo com base na data vigente
        foreach ($processos as $processo) {
            $dataAtual = Carbon::now();
            $dataVigente = Carbon::parse($processo->data_vigente);
            $diferencaMeses = $dataAtual->diffInMonths($dataVigente, false);

            if ($diferencaMeses < 0) {
                $processo->status = 'Vermelho'; // Vencido (vermelho)
            } elseif ($diferencaMeses <= 3) {
                $processo->status = 'Amarelo'; // 3 meses ou menos (amarelo)
            } elseif ($diferencaMeses <= 6) {
                $processo->status = 'Laranja'; // 6 meses ou menos (laranja)
            } elseif ($diferencaMeses > 12) {
                $processo->status = 'sem-cor'; // Mais de 1 ano (sem cor)
            }
        }

        return view('processos.index', compact('processos'));
    }

    public function create()
    {
        // Retorna a view para criar um novo processo de compra
        return view('processos.create');
    }

    public function store(Request $request)
    {
        // Validação do formulário
        $validatedData = $request->validate([
            'numero_processo' => 'required|numeric',
            'descricao' => 'required|string|max:255',
            'data_vigente' => 'required|date',
        ]);

        ProcessoCompra::create([
            'numero_processo' => $validatedData['numero_processo'],
            'descricao' => $validatedData['descricao'],
            'data_vigente' => $validatedData['data_vigente'],
        ]);

        // Redireciona para a página de processos com uma mensagem de sucesso
        return redirect()->route('processos.index')->with('success', 'Processo cadastrado com sucesso!');
    }

}

