<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FonteOrcamento extends Model
{
    protected $table = 'fontes_orcamento';

    protected $fillable = [
        'nome',
        'codigo',
        'tipo',
        'descricao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    // Uma fonte de orçamento pode ter vários orçamentos
    public function orcamentos()
    {
        return $this->hasMany(Orcamento::class);
    }

    // Acessor para obter o valor total dos orçamentos desta fonte
    public function getValorTotalAttribute()
    {
        return $this->orcamentos()->sum('valor_total');
    }

    // Acessor para obter o valor utilizado dos orçamentos desta fonte
    public function getValorUtilizadoAttribute()
    {
        return $this->orcamentos()->sum('valor_utilizado');
    }

    // Acessor para obter o valor disponível dos orçamentos desta fonte
    public function getValorDisponivelAttribute()
    {
        return $this->valor_total - $this->valor_utilizado;
    }

    // Escopo para fontes ativas
    public function scopeAtivas($query)
    {
        return $query->where('ativo', true);
    }

    // Escopo para filtrar por tipo
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
