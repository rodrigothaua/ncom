<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\UserTrackable;

class MovimentacaoOrcamento extends Model
{
    use SoftDeletes, UserTrackable;

    protected $table = 'movimentacoes_orcamento';

    protected $fillable = [
        'alocacao_id',
        'tipo',
        'valor',
        'numero_documento',
        'data_movimentacao',
        'observacoes'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_movimentacao' => 'date'
    ];

    // Relacionamentos
    public function alocacao()
    {
        return $this->belongsTo(AlocacaoOrcamento::class, 'alocacao_id');
    }

    // Scopes
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopePeriodo($query, $dataInicio, $dataFim)
    {
        return $query->whereBetween('data_movimentacao', [$dataInicio, $dataFim]);
    }

    // Métodos auxiliares
    public function getDescricaoCompletaAttribute()
    {
        $desc = "{$this->tipo} - R$ " . number_format($this->valor, 2, ',', '.');
        if ($this->numero_documento) {
            $desc .= " (Doc: {$this->numero_documento})";
        }
        return $desc;
    }

    public function getIsEmpenhoAttribute()
    {
        return $this->tipo === 'Empenho';
    }

    public function getIsLiquidacaoAttribute()
    {
        return $this->tipo === 'Liquidação';
    }

    public function getIsPagamentoAttribute()
    {
        return $this->tipo === 'Pagamento';
    }

    public function getIsCancelamentoAttribute()
    {
        return $this->tipo === 'Cancelamento';
    }
}
