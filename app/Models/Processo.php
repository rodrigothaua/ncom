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
        'valor_consumo',
        'valor_permanente',
        'valor_servico',
        'valor_total',
        'data_inicio',
        'data_vencimento'
    ];

    // Acessor para calcular o valor_total automaticamente
    public function getValorTotalAttribute()
    {
        return $this->valor_consumo + $this->valor_permanente + $this->valor_servico;
    }
}
