<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProcessoService;
use App\Models\Processo;

class HomeController extends Controller
{
    protected $processoService;

    public function __construct(ProcessoService $processoService)
    {
        $this->processoService = $processoService;
    }

    public function index(Request $request)
    {
        $totais = $this->processoService->getTotais();
        $vencimentos = $this->processoService->getVencimentos();

        $totalProcessos = isset($totais['totalProcessos']) ? $totais['totalProcessos'] : 0;
        $valorTotal = isset($totais['valorTotal']) ? $totais['valorTotal'] : 0;

        //Contratos por ano
        $contratosPorAno = $this->processoService->getContratosPorAno();

        return view('home', compact('totalProcessos', 'vencimentos', 'valorTotal', 'totais', 'contratosPorAno'));
    }
}
