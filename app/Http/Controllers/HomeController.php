<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Services\ProcessoService;
use App\Models\Processo;
use App\Models\Contrato;

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
        $contratosPorAno = $this->processoService->getContratosPorAno();
        $dadosCategoriasPorMes = $this->processoService->getDadosCategoriasPorMes();

        $filtroVencimento = $request->input('filtro_vencimento', 'todos');
        $searchTerm = $request->input('search', '');

        $query = Contrato::with(['processo', 'processo.categorias'])
            ->join('processos', 'contratos.processo_id', '=', 'processos.id')
            ->select('contratos.*');

        // Aplicar filtro de vencimento
        $hoje = now();
        switch ($filtroVencimento) {
            case 'menos30':
                $query->whereBetween('data_final_contrato', [$hoje, $hoje->copy()->addDays(30)]);
                break;
            case '30a60':
                $query->whereBetween('data_final_contrato', [$hoje->copy()->addDays(30), $hoje->copy()->addDays(60)]);
                break;
            case '60a90':
                $query->whereBetween('data_final_contrato', [$hoje->copy()->addDays(60), $hoje->copy()->addDays(90)]);
                break;
            case '90a180':
                $query->whereBetween('data_final_contrato', [$hoje->copy()->addDays(90), $hoje->copy()->addDays(180)]);
                break;
            case 'mais180':
                $query->where('data_final_contrato', '>', $hoje->copy()->addDays(180));
                break;
        }

        // Aplicar busca
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('processos.numero_processo', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('processos.requisitante', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('processos.descricao', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('contratos.nome_empresa_contrato', 'LIKE', "%{$searchTerm}%");
            });
        }

        $contratos = $query->orderBy('data_final_contrato', 'asc')->get();

        return view('home', compact(
            'totalProcessos',
            'vencimentos',
            'valorTotal',
            'totais',
            'contratosPorAno',
            'dadosCategoriasPorMes',
            'contratos'
        ));
    }
}
