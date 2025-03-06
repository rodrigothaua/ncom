<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Processo;
use App\Models\Categorias;
use App\Models\Contrato;
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
        // Busca os valores da tabela categorias
        $valorConsumo = Categorias::sum('valor_consumo');
        $valorPermanente = Categorias::sum('valor_permanente');
        $valorServico = Categorias::sum('valor_servico');

        // Calcula o valor total somando as categorias
        $valorTotal = $valorConsumo + $valorPermanente + $valorServico;

        return [
            'valorTotal' => $valorTotal,
            'totalProcessos' => Processo::count(),
            'valorConsumo' => $valorConsumo,
            'valorPermanente' => $valorPermanente,
            'valorServico' => $valorServico
        ];
    }


    public function getVencimentos()
    {
        $hoje = Carbon::today();

        return [
            'totalMenos30Dias' => Processo::whereHas('contratos', function ($query) use ($hoje) {
                $query->where('data_final_contrato', '<=', $hoje->copy()->addDays(30));
            })->count(),
            'total30a60Dias' => Processo::whereHas('contratos', function ($query) use ($hoje) {
                $query->whereBetween('data_final_contrato', [$hoje->copy()->addDays(31), $hoje->copy()->addDays(60)]);
            })->count(),
            'total60a90Dias' => Processo::whereHas('contratos', function ($query) use ($hoje) {
                $query->whereBetween('data_final_contrato', [$hoje->copy()->addDays(61), $hoje->copy()->addDays(90)]);
            })->count(),
            'total90a180Dias' => Processo::whereHas('contratos', function ($query) use ($hoje) {
                $query->whereBetween('data_final_contrato', [$hoje->copy()->addDays(91), $hoje->copy()->addDays(180)]);
            })->count(),
            'totalMais180Dias' => Processo::whereHas('contratos', function ($query) use ($hoje) {
                $query->where('data_final_contrato', '>', $hoje->copy()->addDays(180));
            })->count(),
        ];
    }

    public function getContratosPorAno()
    {
        $contratosPorAno = Contrato::select(DB::raw('YEAR(data_inicial_contrato) as ano'), DB::raw('count(*) as total'))
            ->groupBy('ano')
            ->orderBy('ano')
            ->get();

        return $contratosPorAno;
    }
}
