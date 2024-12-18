<?php

namespace App\Http\Controllers;

use App\Models\ProcessoCompra;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

// Adicione esta linha para importar a classe Controller corretamente
use App\Http\Controllers\Controller;

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

    public function edit($id)
    {
        $processo = ProcessoCompra::findOrFail($id);
        return view('processos.edit', compact('processo'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'numero_processo' => 'required|unique:processo_compras,numero_processo,' . $id,
            'descricao' => 'required',
            'data_vigente' => 'required|date',
        ]);

        $processo = ProcessoCompra::findOrFail($id);
        $processo->update($validated);
        return redirect()->route('processos.index');
    }

    public function destroy($id)
    {
        ProcessoCompra::destroy($id);
        return redirect()->route('processos.index');
    }

    public function getProcessosChartData()
    {
        try {
            $processos = ProcessoCompra::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->get();
    
            return response()->json([
                'labels' => $processos->pluck('status'), // Labels: status
                'totals' => $processos->pluck('total'), // Totais: contagem por status
            ]);
        } catch (\Exception $e) {
            // Em caso de erro, capture a exceção e retorne o erro
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function chartData()
    {
        // Suponha que você tenha um modelo ProcessoCompra
        $processos = ProcessoCompra::all();

        // Processa os dados conforme necessário, por exemplo:
        $chartData = [
            'verde' => $processos->where('status', 'Verde')->count(),
            'laranja' => $processos->where('status', 'Laranja')->count(),
            'amarelo' => $processos->where('status', 'Amarelo')->count(),
            'vermelho' => $processos->where('status', 'Vermelho')->count(),
        ];

        // Retorna os dados em formato JSON
        return response()->json($chartData);
    }


}

