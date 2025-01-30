<?php

namespace App\Services;

use App\Models\Processo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcessoService
{
    public function getProcessos()
    {
        return Processo::all();
    }

    public function getTotais()
    {
        $totalConsumo = Processo::where('categoria', 'Consumo')->count();
        $totalPermanente = Processo::where('categoria', 'Permanente')->count();
        $totalServico = Processo::where('categoria', 'Serviço')->count();

        $valorConsumo = Processo::where('categoria', 'Consumo')->sum('valor_total');
        $valorPermanente = Processo::where('categoria', 'Permanente')->sum('valor_total');
        $valorServico = Processo::where('categoria', 'Serviço')->sum('valor_total');

        return [
            'valorTotal' => Processo::sum('valor_total'),
            'totalConsumo' => $totalConsumo,
            'totalPermanente' => $totalPermanente,
            'totalServico' => $totalServico,
            'totalProcessos' => $totalConsumo + $totalPermanente + $totalServico,
            'valorConsumo' => $valorConsumo,
            'valorPermanente' => $valorPermanente,
            'valorServico' => $valorServico
        ];
    }

    public function getChartData()
    {
        return [
            'labels' => ['Consumo', 'Permanente', 'Serviço'],
            'datasets' => [
                [
                    'label' => 'Total de Processos',
                    'data' => [
                        Processo::where('categoria', 'Consumo')->count(),
                        Processo::where('categoria', 'Permanente')->count(),
                        Processo::where('categoria', 'Serviço')->count()
                    ],
                    'backgroundColor' => ['#198754', '#FFC107', '#DC3545'], // Verde, Amarelo, Vermelho
                ],
            ],
        ];
    }

    public function getVencimentos()
    {
        $hoje = Carbon::today();
        return [
            'totalMenos30Dias' => Processo::where('data_vencimento', '<', $hoje->copy()->subDays(30))->count(),
            'totalEntre30e60Dias' => Processo::whereBetween('data_vencimento', [$hoje->copy()->addDays(30), $hoje->copy()->addDays(60)])->count(),
            'totalEntre60e90Dias' => Processo::whereBetween('data_vencimento', [$hoje->copy()->addDays(60), $hoje->copy()->addDays(90)])->count(),
            'totalEntre90e180Dias' => Processo::whereBetween('data_vencimento', [$hoje->copy()->addDays(90), $hoje->copy()->addDays(180)])->count(),
            'totalMais180Dias' => Processo::where('data_vencimento', '>', $hoje->copy()->addDays(180))->count(),
        ];
    }

    public function getRequisitantesENumerosProcessos()
    {
        return [
            'numerosProcessos' => \DB::table('processo_compras')
                ->select('numero_processo')
                ->distinct()
                ->get(),
            
            'requisitantes' => \DB::table('processo_compras')
                ->select('requisitante')
                ->distinct()
                ->get(),
        ];
    }

    public function getProcessosPorAno()
    {
        $processosPorAno = DB::table('processo_compras')
            ->selectRaw('YEAR(data_inicio) as ano, COUNT(*) as total')
            ->groupBy('ano')
            ->orderBy('ano', 'asc')
            ->get();

        return [
            'labels' => $processosPorAno->pluck('ano')->toArray(), // Pega os anos
            'data' => $processosPorAno->pluck('total')->toArray()  // Pega os totais
        ];
    }

    public function getGraficoMensal()
    {
        $primeiroMes = DB::table('processo_compras')->min('data_inicio');
        $ultimoMes = DB::table('processo_compras')->max('data_inicio');
    
        if (!$primeiroMes || !$ultimoMes) {
            return [
                'labelsBarVertical' => [],
                'dataBarVertical' => [],
                'mediaEixoYBarVertical' => 0
            ];
        }
    
        $inicio = Carbon::parse($primeiroMes)->startOfMonth();
        $fim = Carbon::parse($ultimoMes)->endOfMonth();
    
        $periodo = Carbon::parse($inicio);
        $labelsBarVertical = [];
        $dataBarVertical = [];
    
        while ($periodo->lte($fim)) {
            // Nome do mês (ex.: Janeiro 2025)
            $labelsBarVertical[] = $periodo->translatedFormat('F Y');
    
            // Total dos processos para o mês atual
            $totalMensal = DB::table('processo_compras')
                ->whereYear('data_inicio', $periodo->year)
                ->whereMonth('data_inicio', $periodo->month)
                ->sum('valor_total');
    
            $dataBarVertical[] = $totalMensal ?: 0; // Garantir que meses sem dados sejam 0
            $periodo->addMonth(); // Ir para o próximo mês
        }
    
        // Calcular a média do eixo Y
        $maxValor = !empty($dataBarVertical) ? max($dataBarVertical) : 0;
        $mediaEixoYBarVertical = $maxValor > 0 ? ceil($maxValor / 5) : 1;
    
        return [
            'labelsBarVertical' => $labelsBarVertical,
            'dataBarVertical' => $dataBarVertical,
            'mediaEixoYBarVertical' => $mediaEixoYBarVertical
        ];
    }
}
