<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProcessoService;

class HomeController extends Controller
{
    protected $processoService;

    public function __construct(ProcessoService $processoService)
    {
        $this->processoService = $processoService;
    }

    public function index()
    {
        // Obtém os processos e cálculos via Service
        $processos = $this->processoService->getProcessos();
        $totais = $this->processoService->getTotais();
        $processosChartData = $this->processoService->getChartData();
        $vencimentos = $this->processoService->getVencimentos();
        $requisitantesENumeros = $this->processoService->getRequisitantesENumerosProcessos();
        $processosPorAno = $this->processoService->getProcessosPorAno();
        $graficoMensal = $this->processoService->getGraficoMensal();

        // Retornar a view com os dados
        return view('welcome', array_merge(
            ['processos' => $processos, 'processosChartData' => $processosChartData],
            $totais,
            $vencimentos,
            $requisitantesENumeros,
            $processosPorAno, // Inclui 'labels' e 'data'
            $graficoMensal // Inclui 'labelsBarVertical', 'dataBarVertical' e 'mediaEixoYBarVertical'
        ));
    }
}
