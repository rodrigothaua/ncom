<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Processo extends Model
{
    use HasFactory;

    protected $table = 'processos';

    protected $fillable = [
        'numero_processo',
        'descricao',
        'requisitante',
        'valor_consumo',
        'valor_permanente',
        'valor_servico',
        'valor_total',
        'data_inicio',
        'data_vencimento',
        'data_entrada'
    ];
}
