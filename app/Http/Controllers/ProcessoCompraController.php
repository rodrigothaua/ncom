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
        $hoje = now();

        $processos = ProcessoCompra::orderBy('data_vencimento', 'asc')->get()->map(function ($processo) use ($hoje) {
        $dataVencimento = \Carbon\Carbon::parse($processo->data_vencimento);

        if ($hoje->greaterThan($dataVencimento)) {
            // Prazo vencido
            $processo->status = 'Vermelho';
        } elseif ($hoje->diffInMonths($dataVencimento) <= 3) {
            // Faltam 3 meses ou menos
            $processo->status = 'Amarelo';
        } elseif ($hoje->diffInMonths($dataVencimento) <= 6) {
            // Faltam entre 3 e 6 meses
            $processo->status = 'Laranja';
        } else {
            // Mais de 6 meses
            $processo->status = 'Sem cor';
        }

        return $processo;
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
        // Validação dos campos de entrada
        $validated = $request->validate([
            'numero_processo' => 'required',
            'descricao' => 'required',
            'data_inicio' => 'required|date',
            'data_vencimento' => 'required|date|after:data_inicio',
        ]);

        // Criação de um novo processo
        ProcessoCompra::create([
            'numero_processo' => $validated['numero_processo'],
            'descricao' => $validated['descricao'],
            'data_inicio' => $validated['data_inicio'],
            'data_vencimento' => $validated['data_vencimento'],
        ]);

        // Redireciona para a página de processos com uma mensagem de sucesso
        return redirect()->route('processos.index')->with('success', 'Processo cadastrado com sucesso!');
    }

    private function calcularStatus($dataInicio, $dataVencimento)
    {
        $hoje = Carbon::now()->startOfDay();  // Ignora a parte do horário, considera apenas a data
        $dataVencimento = Carbon::parse($dataVencimento)->startOfDay();  // Também ignora o horário

        // Cálculo do status baseado nas datas
        $diferencaMeses = $hoje->diffInMonths($dataVencimento, false); // False para permitir diferença negativa

        if ($dataVencimento->isPast()) {
            return 'Vermelho';  // Prazo vencido
        }

        if ($diferencaMeses > 12) {
            return 'Há mais de 12 meses';  // Mais de 1 ano para o vencimento
        }

        if ($diferencaMeses <= 3) {
            return 'Amarelo';  // 3 meses ou menos para o vencimento
        }

        if ($diferencaMeses <= 6) {
            return 'Laranja';  // 6 meses ou menos para o vencimento
        }

        return 'Sem cor';  // Caso algum outro critério
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

