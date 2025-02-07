<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Processo extends Model
{
    use HasFactory;

    // Define explicitamente o nome da tabela
    protected $table = 'processo_compras';

    protected $fillable = [
        'numero_processo',
        'descricao',
        'requisitante',
        'categoria',
        'valor_total',
        'data_inicio',
        'data_vencimento',
    ];

    protected $casts = [
        'categoria' => 'array', // Permite armazenar múltiplos valores como JSON
    ];
}
