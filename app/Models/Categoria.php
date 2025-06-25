<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserTracking;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Categoria extends Model
{
    use SoftDeletes, UserTracking;

    protected $table = 'categorias';

    protected $fillable = [
        'processo_id',
        'valor_consumo',
        'valor_permanente',
        'valor_servico'
    ];

    protected $casts = [
        'valor_consumo' => 'decimal:2',
        'valor_permanente' => 'decimal:2',
        'valor_servico' => 'decimal:2'
    ];

    /**
     * Processo relacionado
     */
    public function processo(): BelongsTo
    {
        return $this->belongsTo(Processo::class);
    }

    /**
     * Detalhes da despesa
     */
    public function detalhesDespesa(): HasOne
    {
        return $this->hasOne(DetalhesDespesa::class, 'categorias_id');
    }

    /**
     * Retorna o valor total da categoria
     */
    public function getValorTotalAttribute(): float
    {
        return $this->valor_consumo + $this->valor_permanente + $this->valor_servico;
    }

    /**
     * Usuário que criou o registro
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Usuário que atualizou o registro
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Usuário que excluiu o registro
     */
    public function deleter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Boot do modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Ao criar uma categoria, cria também os detalhes da despesa
        static::created(function ($categoria) {
            $categoria->detalhesDespesa()->create();
        });
    }
}
