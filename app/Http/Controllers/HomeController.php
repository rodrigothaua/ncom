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

        // Consultar os totais de cada categoria no banco de dados
        $totalConsumo = DB::table('processo_compras')->where('categoria', 'Consumo')->count();
        $totalPermanente = DB::table('processo_compras')->where('categoria', 'Permanente')->count();
        $totalServico = DB::table('processo_compras')->where('categoria', 'Serviço')->count();

        // Total geral
        $totalProcessos = $totalConsumo + $totalPermanente + $totalServico;

        // Preparar dados para o gráfico
        $processosChartData = [
            'labels' => ['Consumo', 'Permanente', 'Serviço'],
            'datasets' => [
                [
                    'label' => 'Total de Processos',
                    'data' => [$totalConsumo, $totalPermanente, $totalServico],
                    'backgroundColor' => ['#28a745', '#ffc107', '#dc3545'], // Verde, Amarelo, Vermelho
                ],
            ],
        ];

        //VENCIMENTOS
        // Obtenha a data atual
        $hoje = Carbon::today();

        // Calcular os totais com base nos intervalos de vencimento
        $totalMenos30Dias = DB::table('processo_compras')
            ->where('data_vencimento', '<', $hoje->subDays(30))
            ->count();

        $totalEntre30e60Dias = DB::table('processo_compras')
            ->whereBetween('data_vencimento', [$hoje->addDays(30), $hoje->addDays(60)])
            ->count();

        $totalEntre60e90Dias = DB::table('processo_compras')
            ->whereBetween('data_vencimento', [$hoje->addDays(60), $hoje->addDays(90)])
            ->count();

        $totalEntre90e180Dias = DB::table('processo_compras')
            ->whereBetween('data_vencimento', [$hoje->addDays(90), $hoje->addDays(180)])
            ->count();

        $totalMais180Dias = DB::table('processo_compras')
            ->where('data_vencimento', '>', $hoje->addDays(180))
            ->count();

        // Retornar a view com os dados
        return view('welcome', compact(
            'processos', 
            'valorTotal', 
            'totalProcessos', 
            'totalConsumo', 
            'totalPermanente', 
            'totalServico', 
            'processosChartData',
            'totalMenos30Dias',
            'totalEntre30e60Dias',
            'totalEntre60e90Dias',
            'totalEntre90e180Dias',
            'totalMais180Dias'
        ));
    }
}