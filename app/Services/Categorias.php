<?php

namespace App\Services;

use App\Models\Categorias; // Use o nome correto do modelo
use Illuminate\Support\Carbon;

class ContratoService
{
    public function calcularTotalContratosMesAtual(): float
    {
        return Categorias::whereMonth('created_at', Carbon::now()->month)
            ->get()
            ->sum(function ($categoria) {
                return $categoria->valor_consumo + $categoria->valor_permanente + $categoria->valor_servico;
            });
    }

    public function calcularTotalContratosMesAnterior(): float
    {
        return Categorias::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->get()
            ->sum(function ($categoria) {
                return $categoria->valor_consumo + $categoria->valor_permanente + $categoria->valor_servico;
            });
    }

    public function calcularPorcentagemCrescimento(float $totalAtual, float $totalAnterior): float
    {
        if ($totalAnterior > 0) {
            return (($totalAtual - $totalAnterior) / $totalAnterior) * 100;
        } else {
            return $totalAtual > 0 ? 100 : 0;
        }
    }
}