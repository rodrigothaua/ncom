<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'processo_id',
        'valor_consumo',
        'valor_permanente',
        'valor_servico',
    ];

    public function processo()
    {
        return $this->belongsTo(Processo::class);
    }

    public function detalhesDespesa()
    {
        return $this->hasOne(DetalhesDespesa::class);
    }
}