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
        'nd_servico',
    ];

    public function categorias()
    {
        return $this->belongsTo(Categorias::class);
    }
}