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
        'data_entrada',
        'valor_consumo',
        'valor_permanente',
        'valor_servico',
        'valor_total',
    ];

    public function numeroDespesas()
    {
        return $this->hasMany(NumeroDespesa::class, 'processo_id');
    }
    
    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }
}
