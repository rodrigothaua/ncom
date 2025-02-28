<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\ProcessoService;
use App\Models\Processo;
use App\Models\Categorias; // Adicione esta linha

class DashboardController extends Controller
{
    protected $processoService;

    public function __construct(ProcessoService $processoService)
    {
        $this->processoService = $processoService;
    }

    public function index()
    {
        $totais = $this->processoService->getTotais();

        $totalValorConsumo = isset($totais['valorConsumo']) ? 'R$ ' . number_format($totais['valorConsumo'], 2, ',', '.') : 'R$ 0,00';
        $totalValorPermanente = isset($totais['valorPermanente']) ? 'R$ ' . number_format($totais['valorPermanente'], 2, ',', '.') : 'R$ 0,00';
        $totalValorServico = isset($totais['valorServico']) ? 'R$ ' . number_format($totais['valorServico'], 2, ',', '.') : 'R$ 0,00';
        $totalProcessos = isset($totais['totalProcessos']) ? $totais['totalProcessos'] : 0;

        return view('dashboard.index', compact('totalValorConsumo', 'totalValorPermanente', 'totalValorServico', 'totalProcessos'));
    }
}