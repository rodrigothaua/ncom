<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProcessoCompra;

class ProcessosChartController extends Controller
{
    public function getPieChartData()
    {
        // Calcula a quantidade de processos por cor
        $verde = ProcessoCompra::where('data_vencimento', '>', now()->addDays(30))->count();
        $amarelo = ProcessoCompra::whereBetween('data_vencimento', [now(), now()->addDays(30)])->count();
        $vermelho = ProcessoCompra::where('data_vencimento', '<', now())->count();

        // Retorna os dados como JSON
        return response()->json([
            'verde' => $verde,
            'amarelo' => $amarelo,
            'vermelho' => $vermelho,
        ]);
    }
}
