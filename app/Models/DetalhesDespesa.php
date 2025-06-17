<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalhesDespesa extends Model
{
    use HasFactory;

    protected $table = 'detalhes_despesa';

    protected $fillable = [
        'categorias_id',
        'pa_consumo',
        'pa_permanente',
        'pa_servico',
        'nd_consumo',
        'nd_permanente',
        'nd_servico'
    ];

    // Relacionamentos existentes
    public function categoria()
    {
        return $this->belongsTo(Categorias::class, 'categorias_id');
    }

    public function processo()
    {
        return $this->hasOneThrough(
            Processo::class,
            Categorias::class,
            'id', // Foreign key on categorias table...
            'id', // Foreign key on processos table...
            'categorias_id', // Local key on detalhes_despesa table...
            'processo_id' // Local key on categorias table...
        );
    }

    // Novo relacionamento com orçamentos
    public function orcamentos()
    {
        return $this->belongsToMany(Orcamento::class, 'detalhes_despesa_orcamento')
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

    // Métodos para gerenciar valores orçamentários
    public function getValorTotalAlocadoAttribute()
    {
        return $this->orcamentos()->sum('pivot.valor_alocado');
    }

    public function getValorTotalUtilizadoAttribute()
    {
        return $this->orcamentos()->sum('pivot.valor_utilizado');
    }

    public function getValorDisponivelAttribute()
    {
        return $this->valor_total_alocado - $this->valor_total_utilizado;
    }

    // Método para verificar se tem orçamento suficiente
    public function temOrcamentoSuficiente($valor)
    {
        return $this->valor_disponivel >= $valor;
    }

    // Método para obter alocações por status
    public function getAlocacoesPorStatus($status)
    {
        return $this->orcamentos()
            ->wherePivot('status', $status)
            ->get();
    }

    // Método para obter alocações disponíveis
    public function getAlocacoesDisponiveis()
    {
        return $this->orcamentos()
            ->whereRaw('detalhes_despesa_orcamento.valor_alocado > detalhes_despesa_orcamento.valor_utilizado')
            ->whereIn('detalhes_despesa_orcamento.status', ['Planejado', 'Empenhado'])
            ->get();
    }

    // Método para registrar utilização de orçamento
    public function registrarUtilizacaoOrcamento($orcamentoId, $valor, $numeroEmpenho, $dataEmpenho)
    {
        $orcamento = $this->orcamentos()->find($orcamentoId);
        
        if (!$orcamento) {
            throw new \Exception('Orçamento não está vinculado a este detalhe de despesa');
        }

        return $orcamento->registrarUtilizacao($this, $valor, $numeroEmpenho, $dataEmpenho);
    }
}
