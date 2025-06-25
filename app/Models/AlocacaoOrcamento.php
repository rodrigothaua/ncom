<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\UserTrackable;

class AlocacaoOrcamento extends Model
{
    use SoftDeletes, UserTrackable;

    protected $table = 'alocacoes_orcamento';

    protected $fillable = [
        'orcamento_id',
        'categoria_despesa_id',
        'processo_id',
        'valor_alocado',
        'valor_utilizado',
        'numero_nota_empenho',
        'data_empenho',
        'status',
        'observacoes'
    ];

    protected $casts = [
        'valor_alocado' => 'decimal:2',
        'valor_utilizado' => 'decimal:2',
        'data_empenho' => 'date'
    ];

    // Relacionamentos
    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaDespesa::class, 'categoria_despesa_id');
    }

    public function processo()
    {
        return $this->belongsTo(Processo::class);
    }

    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoOrcamento::class, 'alocacao_id');
    }

    // Atributos calculados
    public function getValorDisponivelAttribute()
    {
        return $this->valor_alocado - $this->valor_utilizado;
    }

    public function getPercentualUtilizadoAttribute()
    {
        if ($this->valor_alocado > 0) {
            return ($this->valor_utilizado / $this->valor_alocado) * 100;
        }
        return 0;
    }

    // Scopes
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDisponiveis($query)
    {
        return $query->whereColumn('valor_utilizado', '<', 'valor_alocado')
            ->whereIn('status', ['Planejado', 'Empenhado', 'Liquidado']);
    }

    // Métodos de negócio
    public function empenhar($valor, $numeroEmpenho, $dataEmpenho, $observacoes = null)
    {
        if ($valor > $this->valor_disponivel) {
            throw new \Exception('Valor de empenho excede o disponível na alocação');
        }

        if ($this->status !== 'Planejado') {
            throw new \Exception('Só é possível empenhar alocações com status Planejado');
        }

        DB::transaction(function () use ($valor, $numeroEmpenho, $dataEmpenho, $observacoes) {
            // Atualiza a alocação
            $this->valor_utilizado += $valor;
            $this->numero_nota_empenho = $numeroEmpenho;
            $this->data_empenho = $dataEmpenho;
            $this->status = 'Empenhado';
            $this->save();

            // Registra a movimentação
            $this->movimentacoes()->create([
                'tipo' => 'Empenho',
                'valor' => $valor,
                'numero_documento' => $numeroEmpenho,
                'data_movimentacao' => $dataEmpenho,
                'observacoes' => $observacoes
            ]);

            // Atualiza o valor utilizado do orçamento
            $this->orcamento()->increment('valor_utilizado', $valor);
        });

        return true;
    }

    public function liquidar($valor, $numeroDocumento, $observacoes = null)
    {
        if ($this->status !== 'Empenhado') {
            throw new \Exception('Só é possível liquidar alocações empenhadas');
        }

        if ($valor > $this->valor_utilizado) {
            throw new \Exception('Valor de liquidação excede o valor empenhado');
        }

        $this->status = 'Liquidado';
        $this->save();

        $this->movimentacoes()->create([
            'tipo' => 'Liquidação',
            'valor' => $valor,
            'numero_documento' => $numeroDocumento,
            'data_movimentacao' => now(),
            'observacoes' => $observacoes
        ]);

        return true;
    }

    public function pagar($valor, $numeroDocumento, $observacoes = null)
    {
        if ($this->status !== 'Liquidado') {
            throw new \Exception('Só é possível pagar alocações liquidadas');
        }

        if ($valor > $this->valor_utilizado) {
            throw new \Exception('Valor de pagamento excede o valor liquidado');
        }

        $this->status = 'Pago';
        $this->save();

        $this->movimentacoes()->create([
            'tipo' => 'Pagamento',
            'valor' => $valor,
            'numero_documento' => $numeroDocumento,
            'data_movimentacao' => now(),
            'observacoes' => $observacoes
        ]);

        return true;
    }

    public function cancelar($observacoes = null)
    {
        if (!in_array($this->status, ['Planejado', 'Empenhado'])) {
            throw new \Exception('Não é possível cancelar alocações liquidadas ou pagas');
        }

        DB::transaction(function () use ($observacoes) {
            // Se estava empenhado, precisa reverter o valor utilizado do orçamento
            if ($this->status === 'Empenhado') {
                $this->orcamento()->decrement('valor_utilizado', $this->valor_utilizado);
            }

            $this->status = 'Cancelado';
            $this->save();

            $this->movimentacoes()->create([
                'tipo' => 'Cancelamento',
                'valor' => $this->valor_utilizado,
                'data_movimentacao' => now(),
                'observacoes' => $observacoes
            ]);
        });

        return true;
    }
}
