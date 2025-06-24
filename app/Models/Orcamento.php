<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\UserTrackable;

class Orcamento extends Model
{
    use UserTrackable;

    protected $table = 'orcamentos';

    protected $fillable = [
        'fonte_orcamento_id',
        'numero_orcamento',
        'valor_total',
        'valor_utilizado',
        'ano_exercicio',
        'descricao',
        'parlamentar',
        'partido',
        'numero_convenio',
        'data_inicio',
        'data_fim',
        'status',
        'observacoes'
    ];

    protected $casts = [
        'valor_total' => 'decimal:2',
        'valor_utilizado' => 'decimal:2',
        'data_inicio' => 'date',
        'data_fim' => 'date'
    ];

    // Relacionamento com a fonte do orçamento
    public function fonte()
    {
        return $this->belongsTo(FonteOrcamento::class, 'fonte_orcamento_id');
    }

    // Relacionamento com os detalhes de despesa através da tabela pivot
    public function detalhesDespesa()
    {
        return $this->belongsToMany(DetalhesDespesa::class, 'detalhes_despesa_orcamento')
            ->withPivot([
                'valor_alocado',
                'valor_utilizado',
                'numero_nota_empenho',
                'data_empenho',
                'status',
                'observacoes'
            ])
            ->withTimestamps();
    }

    // Acessor para obter o valor disponível
    public function getValorDisponivelAttribute()
    {
        return $this->valor_total - $this->valor_utilizado;
    }

    // Acessor para obter o percentual utilizado
    public function getPercentualUtilizadoAttribute()
    {
        if ($this->valor_total > 0) {
            return ($this->valor_utilizado / $this->valor_total) * 100;
        }
        return 0;
    }

    // Método para alocar valor a um detalhe de despesa
    public function alocarParaDetalhe(DetalhesDespesa $detalhe, $valor, $observacoes = null)
    {
        if ($valor > $this->valor_disponivel) {
            throw new \Exception('Valor alocado excede o disponível no orçamento');
        }

        $this->detalhesDespesa()->attach($detalhe->id, [
            'valor_alocado' => $valor,
            'observacoes' => $observacoes,
            'status' => 'Planejado'
        ]);

        return true;
    }

    // Método para registrar utilização de valor em um detalhe de despesa
    public function registrarUtilizacao(DetalhesDespesa $detalhe, $valor, $numeroEmpenho, $dataEmpenho)
    {
        $alocacao = $this->detalhesDespesa()
            ->where('detalhes_despesa_id', $detalhe->id)
            ->first();

        if (!$alocacao) {
            throw new \Exception('Detalhe de despesa não está alocado a este orçamento');
        }

        $valorDisponivel = $alocacao->pivot->valor_alocado - $alocacao->pivot->valor_utilizado;
        if ($valor > $valorDisponivel) {
            throw new \Exception('Valor utilizado excede o valor alocado');
        }

        $this->detalhesDespesa()->updateExistingPivot($detalhe->id, [
            'valor_utilizado' => $alocacao->pivot->valor_utilizado + $valor,
            'numero_nota_empenho' => $numeroEmpenho,
            'data_empenho' => $dataEmpenho,
            'status' => 'Empenhado'
        ]);

        $this->increment('valor_utilizado', $valor);

        return true;
    }

    // Escopos locais para filtros comuns
    public function scopeAnoExercicio($query, $ano)
    {
        return $query->where('ano_exercicio', $ano);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeEmExecucao($query)
    {
        return $query->where('status', 'Em Execução');
    }

    public function scopeDisponiveis($query)
    {
        return $query->whereColumn('valor_utilizado', '<', 'valor_total')
            ->whereIn('status', ['Aprovado', 'Em Execução']);
    }
}
