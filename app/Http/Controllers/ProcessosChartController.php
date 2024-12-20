<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ProcessosChartController extends Controller
{
    public function getProcessosData()
    {
        $hoje = now();

        $processos = DB::table('processo_compras')->get();

        $resumo = [
            'vermelho' => 0,
            'amarelo' => 0,
            'laranja' => 0,
            'sem_cor' => 0
        ];

        foreach ($processos as $processo) {
            $dataVencimento = \Carbon\Carbon::parse($processo->data_vencimento);

            if ($hoje->greaterThan($dataVencimento)) {
                $resumo['vermelho']++;
            } elseif ($hoje->diffInMonths($dataVencimento) <= 3) {
                $resumo['amarelo']++;
            } elseif ($hoje->diffInMonths($dataVencimento) <= 6) {
                $resumo['laranja']++;
            } else {
                $resumo['sem_cor']++;
            }
        }

        return response()->json($resumo);
    }
}
