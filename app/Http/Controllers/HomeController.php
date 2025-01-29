<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Processo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Obtém todos os processos
        $processos = Processo::all();

        // Calcula os totais
        $valorTotal = $processos->sum('valor_total');

        // Totais por categoria
        $totalConsumo = Processo::where('categoria', 'Consumo')->count();
        $totalPermanente = Processo::where('categoria', 'Permanente')->count();
        $totalServico = Processo::where('categoria', 'Serviço')->count();

        // Valores por categoria
        $valorConsumo = Processo::where('categoria', 'Consumo')->sum('valor_total');
        $valorPermanente = Processo::where('categoria', 'Permanente')->sum('valor_total');
        $valorServico = Processo::where('categoria', 'Serviço')->sum('valor_total');

        // Total geral
        $totalProcessos = $totalConsumo + $totalPermanente + $totalServico;

        // Preparar dados para o gráfico
        $processosChartData = [
            'labels' => ['Consumo', 'Permanente', 'Serviço'],
            'datasets' => [
                [
                    'label' => 'Total de Processos',
                    'data' => [$totalConsumo, $totalPermanente, $totalServico],
                    'backgroundColor' => ['#198754', '#FFC107', '#DC3545'], // Verde, Amarelo, Vermelho
                ],
            ],
        ];

        // Agrupar processos por ano e contar o total
        $processosPorAno = DB::table('processo_compras')
            ->selectRaw('YEAR(data_inicio) as ano, COUNT(*) as total')
            ->groupBy('ano')
            ->orderBy('ano', 'asc')
            ->get();

        // Preparar dados para o gráfico
        $labels = $processosPorAno->pluck('ano'); // Pega os anos
        $data = $processosPorAno->pluck('total'); // Pega os totais
        
        // Obter o primeiro e o último mês com base no banco de dados
        $primeiroMes = DB::table('processo_compras')->min('data_inicio');
        $ultimoMes = DB::table('processo_compras')->max('data_inicio');

        if (!$primeiroMes || !$ultimoMes) {
            $labelsBarVertical = [];
            $dataBarVertical = [];
            $mediaEixoYBarVertical = 0;
        } else {
            // Criar intervalos de meses
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
        }

        //VENCIMENTOS
        // Obtenha a data atual
        $hoje = Carbon::today();

        // Calcular os totais com base nos intervalos de vencimento
        $totalMenos30Dias = Processo::where('data_vencimento', '<', $hoje->copy()->subDays(30))->count();

        $totalEntre30e60Dias = Processo::whereBetween('data_vencimento', [$hoje->copy()->addDays(30), $hoje->copy()->addDays(60)])->count();

        $totalEntre60e90Dias = Processo::whereBetween('data_vencimento', [$hoje->copy()->addDays(60), $hoje->copy()->addDays(90)])->count();

        $totalEntre90e180Dias = Processo::whereBetween('data_vencimento', [$hoje->copy()->addDays(90), $hoje->copy()->addDays(180)])->count();

        $totalMais180Dias = Processo::where('data_vencimento', '>', $hoje->copy()->addDays(180))->count();

        // Retornar a view com os dados
        return view('welcome', compact(
            'processos', 
            'valorTotal', 
            'totalProcessos', 
            'totalConsumo', 
            'totalPermanente', 
            'totalServico', 
            'valorConsumo', 
            'valorPermanente', 
            'valorServico', 
            'processosChartData',
            'totalMenos30Dias',
            'totalEntre30e60Dias',
            'totalEntre60e90Dias',
            'totalEntre90e180Dias',
            'totalMais180Dias',
            'labels',
            'data',
            'labelsBarVertical',
            'dataBarVertical',
            'mediaEixoYBarVertical'
        ));
    }
}