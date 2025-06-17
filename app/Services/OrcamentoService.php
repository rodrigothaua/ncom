<?php

namespace App\Services;

use App\Models\Orcamento;
use App\Models\FonteOrcamento;
use App\Models\DetalhesDespesa;
use Illuminate\Support\Facades\DB;
use Exception;

class OrcamentoService
{
    /**
     * Cria uma nova fonte de orçamento
     */
    public function criarFonte(array $dados)
    {
        return FonteOrcamento::create($dados);
    }

    /**
     * Cria um novo orçamento
     */
    public function criarOrcamento(array $dados)
    {
        return DB::transaction(function () use ($dados) {
            return Orcamento::create($dados);
        });
    }

    /**
     * Aloca orçamento para um detalhe de despesa
     */
    public function alocarOrcamento($orcamentoId, $detalheId, $valor, $observacoes = null)
    {
        return DB::transaction(function () use ($orcamentoId, $detalheId, $valor, $observacoes) {
            $orcamento = Orcamento::findOrFail($orcamentoId);
            $detalhe = DetalhesDespesa::findOrFail($detalheId);

            return $orcamento->alocarParaDetalhe($detalhe, $valor, $observacoes);
        });
    }

    /**
     * Registra utilização de orçamento
     */
    public function registrarUtilizacao($orcamentoId, $detalheId, $valor, $numeroEmpenho, $dataEmpenho)
    {
        return DB::transaction(function () use ($orcamentoId, $detalheId, $valor, $numeroEmpenho, $dataEmpenho) {
            $orcamento = Orcamento::findOrFail($orcamentoId);
            $detalhe = DetalhesDespesa::findOrFail($detalheId);

            return $orcamento->registrarUtilizacao($detalhe, $valor, $numeroEmpenho, $dataEmpenho);
        });
    }

    /**
     * Obtém resumo dos orçamentos por fonte
     */
    public function getResumoOrcamentosPorFonte($ano = null)
    {
        $query = FonteOrcamento::with(['orcamentos' => function ($query) use ($ano) {
            if ($ano) {
                $query->where('ano_exercicio', $ano);
            }
        }])->ativas();

        return $query->get()->map(function ($fonte) {
            return [
                'fonte' => $fonte->nome,
                'tipo' => $fonte->tipo,
                'valor_total' => $fonte->valor_total,
                'valor_utilizado' => $fonte->valor_utilizado,
                'valor_disponivel' => $fonte->valor_disponivel,
                'percentual_utilizado' => $fonte->valor_total > 0 
                    ? ($fonte->valor_utilizado / $fonte->valor_total) * 100 
                    : 0
            ];
        });
    }

    /**
     * Obtém resumo dos orçamentos por status
     */
    public function getResumoOrcamentosPorStatus($ano = null)
    {
        $query = Orcamento::query();
        
        if ($ano) {
            $query->where('ano_exercicio', $ano);
        }

        return $query
            ->select('status', 
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(valor_total) as valor_total'),
                DB::raw('SUM(valor_utilizado) as valor_utilizado')
            )
            ->groupBy('status')
            ->get()
            ->map(function ($item) {
                return [
                    'status' => $item->status,
                    'quantidade' => $item->total,
                    'valor_total' => $item->valor_total,
                    'valor_utilizado' => $item->valor_utilizado,
                    'valor_disponivel' => $item->valor_total - $item->valor_utilizado,
                    'percentual_utilizado' => $item->valor_total > 0 
                        ? ($item->valor_utilizado / $item->valor_total) * 100 
                        : 0
                ];
            });
    }

    /**
     * Obtém detalhes de despesa com orçamentos alocados
     */
    public function getDetalhesComOrcamentos()
    {
        return DetalhesDespesa::with(['orcamentos', 'categoria', 'processo'])
            ->whereHas('orcamentos')
            ->get()
            ->map(function ($detalhe) {
                return [
                    'id' => $detalhe->id,
                    'processo' => $detalhe->processo->numero_processo,
                    'categoria' => $detalhe->categoria->descricao,
                    'valor_total_alocado' => $detalhe->valor_total_alocado,
                    'valor_total_utilizado' => $detalhe->valor_total_utilizado,
                    'valor_disponivel' => $detalhe->valor_disponivel,
                    'orcamentos' => $detalhe->orcamentos->map(function ($orcamento) {
                        return [
                            'fonte' => $orcamento->fonte->nome,
                            'numero_orcamento' => $orcamento->numero_orcamento,
                            'valor_alocado' => $orcamento->pivot->valor_alocado,
                            'valor_utilizado' => $orcamento->pivot->valor_utilizado,
                            'status' => $orcamento->pivot->status
                        ];
                    })
                ];
            });
    }

    /**
     * Obtém orçamentos próximos do vencimento
     */
    public function getOrcamentosProximosVencimento($diasLimite = 30)
    {
        return Orcamento::whereNotNull('data_fim')
            ->where('data_fim', '<=', now()->addDays($diasLimite))
            ->whereIn('status', ['Aprovado', 'Em Execução'])
            ->with('fonte')
            ->get()
            ->map(function ($orcamento) {
                return [
                    'numero_orcamento' => $orcamento->numero_orcamento,
                    'fonte' => $orcamento->fonte->nome,
                    'valor_total' => $orcamento->valor_total,
                    'valor_utilizado' => $orcamento->valor_utilizado,
                    'valor_disponivel' => $orcamento->valor_disponivel,
                    'data_fim' => $orcamento->data_fim,
                    'dias_restantes' => now()->diffInDays($orcamento->data_fim, false)
                ];
            });
    }
}
