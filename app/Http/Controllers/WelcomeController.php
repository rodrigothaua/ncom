<?php

namespace App\Http\Controllers;

use App\Models\Processo;
use App\Services\ProcessoService;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    protected $processoService;

    public function __construct(ProcessoService $processoService)
    {
        $this->processoService = $processoService;
    }

    public function index()
    {
        $totais = $this->processoService->getTotais();
        $vencimentos = $this->processoService->getVencimentos();
        $categorias = $this->processoService->getTotaisPorCategoria();
        $processos = $this->processoService->getProcessos();

        // Preparar dados para o gráfico
        $labels = array_keys($categorias);
        $data = array_values($categorias);

        // Certifique-se de que $totais e $vencimentos são arrays
        $totais = is_array($totais) ? $totais : [];
        $vencimentos = is_array($vencimentos) ? $vencimentos : [];

        // Obtém o total de processos do array $totais
        $totalContratos = isset($totais['totalProcessos']) ? $totais['totalProcessos'] : 0;

        return view('welcome', array_merge([
            'processos' => $processos,
            'labels' => $labels,
            'data' => $data,
            'totalContratos' => $totalContratos, // Passa o total de contratos para a view
        ], $totais, $vencimentos));
    }

    public function filter(Request $request)
    {
        $processos = $this->processoService->getFiltro($request);
        return response()->json($processos);
    }
}