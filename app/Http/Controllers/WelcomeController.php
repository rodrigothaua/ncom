<?php

namespace App\Http\Controllers;

use App\Models\Processo;

class WelcomeController extends Controller
{
    public function index()
    {
        // Importar os processos
        $processos = Processo::all();

        // Valor total de todos os processos
        $valorTotal = Processo::sum('valor_total');

        // Valores por categoria
        $valoresPorCategoria = Processo::select('categoria', DB::raw('SUM(valor_total) as total'))
            ->groupBy('categoria')
            ->pluck('total', 'categoria');

        // Processos vencendo por período
        // Calcular vencimentos em categorias
        $hoje = Carbon::today();

        $vencemMenos30Dias = $processos->where('data_vigente', '<', $hoje->copy()->addDays(30));
        $vencem30a60Dias = $processos->whereBetween('data_vigente', [$hoje->copy()->addDays(30), $hoje->copy()->addDays(60)]);
        $vencem60a90Dias = $processos->whereBetween('data_vigente', [$hoje->copy()->addDays(60), $hoje->copy()->addDays(90)]);
        $vencem90a180Dias = $processos->whereBetween('data_vigente', [$hoje->copy()->addDays(90), $hoje->copy()->addDays(180)]);
        $vencemMais180Dias = $processos->where('data_vigente', '>', $hoje->copy()->addDays(180));

        // Valores totais de cada categoria
        $totalMenos30Dias = $vencemMenos30Dias->sum('valor_total');
        $total30a60Dias = $vencem30a60Dias->sum('valor_total');
        $total60a90Dias = $vencem60a90Dias->sum('valor_total');
        $total90a180Dias = $vencem90a180Dias->sum('valor_total');
        $totalMais180Dias = $vencemMais180Dias->sum('valor_total');

        /// Enviar para a view
        return view('welcome', compact(
            'totalMenos30Dias',
            'total30a60Dias',
            'total60a90Dias',
            'total90a180Dias',
            'totalMais180Dias'
        ));
    }
}

