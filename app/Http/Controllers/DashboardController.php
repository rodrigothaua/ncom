<?php

namespace App\Http\Controllers;

use App\Services\ContratoService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected $contratoService;

    public function __construct(ContratoService $contratoService)
    {
        $this->contratoService = $contratoService;
    }

    public function index(): View
    {
        $totalAtual = $this->contratoService->calcularTotalContratosMesAtual();
        $totalAnterior = $this->contratoService->calcularTotalContratosMesAnterior();
        $porcentagem = $this->contratoService->calcularPorcentagemCrescimento($totalAtual, $totalAnterior);

        return view('dashboard.index', [
            'totalAtual' => $totalAtual,
            'porcentagem' => $porcentagem,
        ]);
    }
}