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
                    'backgroundColor' => ['#28a745', '#ffc107', '#dc3545'], // Verde, Amarelo, Vermelho
                ],
            ],
        ];

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
            'totalMais180Dias'
        ));
    }
}