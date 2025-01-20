<?php

namespace App\Http\Controllers;

use App\Models\Processo;

class WelcomeController extends Controller
{
    public function index()
    {
        // Buscar todos os processos
        $processos = Processo::all();

        // Calcular o valor total de todos os processos (usando 'valor_total')
        $valorTotal = $processos->sum('valor_total');

        // Agrupar os processos por categoria e calcular o total de cada categoria
        $totaisPorCategoria = Processo::select('categoria', \DB::raw('SUM(valor_total) as total'))
            ->groupBy('categoria')
            ->get();

        // Retornar a view com os dados
        return view('welcome', compact('processos', 'valorTotal', 'totaisPorCategoria'));
    }
}

