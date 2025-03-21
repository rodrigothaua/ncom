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
}
