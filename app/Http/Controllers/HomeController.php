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
        // Obtém todos os processos do banco de dados
        $processos = Processo::all(); // Você pode usar métodos como where(), orderBy(), etc., dependendo dos requisitos

        // Obtém os processos e cálculos via Service
        $processos = $this->processoService->getProcessos();
        $totais = $this->processoService->getTotais();
        $vencimentos = $this->processoService->getVencimentos();

        // Obtém os dados dos processos
        $vencimentos = $this->processoService->getVencimentos();

        // Obtém as quantidades de processos por categoria
        
    
        // Passando dados para a view
        return view('welcome', array_merge(
            ['processos' => $processos],
            $totais,
            $vencimentos
        ));
    }
}
