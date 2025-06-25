<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\UserTrackable;

class CategoriaDespesa extends Model
{
    use SoftDeletes, UserTrackable;

    protected $table = 'categorias_despesa';

    protected $fillable = [
        'nome',
        'codigo',
        'descricao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    // Relacionamentos
    public function alocacoes()
    {
        return $this->hasMany(AlocacaoOrcamento::class);
    }

    // Scopes
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }

    // Atributos calculados
    public function getValorTotalAlocadoAttribute()
    {
        return $this->alocacoes()->sum('valor_alocado');
    }

    public function getValorTotalUtilizadoAttribute()
    {
        return $this->alocacoes()->sum('valor_utilizado');
    }

    public function getValorDisponivelAttribute()
    {
        return $this->valor_total_alocado - $this->valor_total_utilizado;
    }

    // Métodos auxiliares
    public function temAlocacoesAtivas()
    {
        return $this->alocacoes()
            ->whereIn('status', ['Planejado', 'Empenhado', 'Liquidado'])
            ->exists();
    }
}
