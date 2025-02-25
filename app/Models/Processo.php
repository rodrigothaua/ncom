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
        'requisitante',
        'descricao',
        'data_entrada',
        'valor_consumo',
        'valor_permanente',
        'valor_servico',
        'valor_total'
    ];

    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }

    public function paProcessos()
    {
        return $this->hasMany(ProcessoPA::class, 'processo_id');
    }
}
