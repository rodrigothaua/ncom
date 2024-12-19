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

    public function getProcessosPieChartData()
    {
        try {
            // Obtenha os dados de todos os processos
            $processos = ProcessoCompra::all();

            // Inicialize contadores para os grupos de data
            $semCor = 0;
            $maisDeUmAno = 0;
            $ateSeisMeses = 0;
            $ateTresMeses = 0;
            $vencido = 0;

            foreach ($processos as $processo) {
                $dataVigente = \Carbon\Carbon::parse($processo->data_vigente);
                $hoje = now();

                if ($dataVigente->isPast()) {
                    $vencido++;
                } elseif ($hoje->diffInMonths($dataVigente) <= 3) {
                    $ateTresMeses++;
                } elseif ($hoje->diffInMonths($dataVigente) <= 6) {
                    $ateSeisMeses++;
                } elseif ($hoje->diffInMonths($dataVigente) > 12) {
                    $semCor++;
                }
            }

            // Total de processos
            $total = $processos->count();

            // Retorne os dados em formato JSON
            return response()->json([
                'labels' => ['Vencido', 'Até 3 meses', 'Até 6 meses', 'Mais de 1 ano'],
                'data' => [$vencido, $ateTresMeses, $ateSeisMeses, $semCor],
                'total' => $total,
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao gerar dados para o gráfico: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao processar os dados'], 500);
        }
    }



}

