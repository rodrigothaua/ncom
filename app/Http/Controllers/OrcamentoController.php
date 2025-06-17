<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\FonteOrcamento;
use App\Models\DetalhesDespesa;
use App\Services\OrcamentoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrcamentoController extends Controller
{
    protected $orcamentoService;

    public function __construct(OrcamentoService $orcamentoService)
    {
        $this->orcamentoService = $orcamentoService;
    }

    public function index(Request $request)
    {
        $ano = $request->get('ano', date('Y'));
        
        $resumoPorFonte = $this->orcamentoService->getResumoOrcamentosPorFonte($ano);
        $resumoPorStatus = $this->orcamentoService->getResumoOrcamentosPorStatus($ano);
        $orcamentosVencendo = $this->orcamentoService->getOrcamentosProximosVencimento();
        
        return view('orcamentos.index', compact(
            'resumoPorFonte',
            'resumoPorStatus',
            'orcamentosVencendo',
            'ano'
        ));
    }

    public function fontes()
    {
        $fontes = FonteOrcamento::orderBy('nome')->get();
        return view('orcamentos.fontes.index', compact('fontes'));
    }

    public function criarFonte()
    {
        return view('orcamentos.fontes.criar');
    }

    public function salvarFonte(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255|unique:fontes_orcamento',
            'codigo' => 'required|string|max:255|unique:fontes_orcamento',
            'tipo' => 'required|string|in:Federal,Estadual,Municipal,Emenda Parlamentar,Outro',
            'descricao' => 'nullable|string'
        ]);

        $this->orcamentoService->criarFonte($request->all());

        return redirect()->route('orcamentos.fontes')
            ->with('success', 'Fonte de orçamento criada com sucesso!');
    }

    public function orcamentos(Request $request)
    {
        $orcamentos = Orcamento::with('fonte')
            ->when($request->fonte_id, function ($query, $fonteId) {
                return $query->where('fonte_orcamento_id', $fonteId);
            })
            ->when($request->ano, function ($query, $ano) {
                return $query->where('ano_exercicio', $ano);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $fontes = FonteOrcamento::ativas()->orderBy('nome')->get();

        return view('orcamentos.lista', compact('orcamentos', 'fontes'));
    }

    public function criar()
    {
        $fontes = FonteOrcamento::ativas()->orderBy('nome')->get();
        return view('orcamentos.criar', compact('fontes'));
    }

    public function salvar(Request $request)
    {
        $request->validate([
            'fonte_orcamento_id' => 'required|exists:fontes_orcamento,id',
            'numero_orcamento' => 'required|string|max:255|unique:orcamentos',
            'valor_total' => 'required|numeric|min:0',
            'ano_exercicio' => 'required|string|size:4',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after:data_inicio',
            'descricao' => 'nullable|string',
            'parlamentar' => 'nullable|string|max:255',
            'partido' => 'nullable|string|max:255',
            'numero_convenio' => 'nullable|string|max:255',
            'observacoes' => 'nullable|string'
        ]);

        $this->orcamentoService->criarOrcamento($request->all());

        return redirect()->route('orcamentos.orcamentos')
            ->with('success', 'Orçamento criado com sucesso!');
    }

    public function detalhes($id)
    {
        $orcamento = Orcamento::with(['fonte', 'detalhesDespesa.processo'])->findOrFail($id);
        return view('orcamentos.detalhes', compact('orcamento'));
    }

    public function alocar($id)
    {
        $orcamento = Orcamento::findOrFail($id);
        $detalhes = DetalhesDespesa::whereDoesntHave('orcamentos', function ($query) use ($id) {
            $query->where('orcamento_id', $id);
        })->get();

        return view('orcamentos.alocar', compact('orcamento', 'detalhes'));
    }

    public function salvarAlocacao(Request $request, $id)
    {
        $request->validate([
            'detalhes_despesa_id' => 'required|exists:detalhes_despesa,id',
            'valor' => 'required|numeric|min:0',
            'observacoes' => 'nullable|string'
        ]);

        try {
            $this->orcamentoService->alocarOrcamento(
                $id,
                $request->detalhes_despesa_id,
                $request->valor,
                $request->observacoes
            );

            return redirect()->route('orcamentos.detalhes', $id)
                ->with('success', 'Orçamento alocado com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function registrarUtilizacao($id, $detalheId)
    {
        $orcamento = Orcamento::findOrFail($id);
        $detalhe = DetalhesDespesa::findOrFail($detalheId);
        $alocacao = $orcamento->detalhesDespesa()->findOrFail($detalheId)->pivot;

        return view('orcamentos.utilizar', compact('orcamento', 'detalhe', 'alocacao'));
    }

    public function salvarUtilizacao(Request $request, $id, $detalheId)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0',
            'numero_empenho' => 'required|string|max:255',
            'data_empenho' => 'required|date'
        ]);

        try {
            $this->orcamentoService->registrarUtilizacao(
                $id,
                $detalheId,
                $request->valor,
                $request->numero_empenho,
                $request->data_empenho
            );

            return redirect()->route('orcamentos.detalhes', $id)
                ->with('success', 'Utilização registrada com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function aprovar($id)
    {
        $orcamento = Orcamento::findOrFail($id);
        $orcamento->status = 'Aprovado';
        $orcamento->save();

        return back()->with('success', 'Orçamento aprovado com sucesso!');
    }

    public function reprovar($id)
    {
        $orcamento = Orcamento::findOrFail($id);
        $orcamento->status = 'Reprovado';
        $orcamento->save();

        return back()->with('success', 'Orçamento reprovado!');
    }
}
