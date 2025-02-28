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
        'modalidade',
        'procedimentos_auxiliares'
    ];

    public function categorias()
    {
        return $this->hasOne(Categorias::class);
    }
    
    public function contratos()
    {
        return $this->hasMany(Contrato::class);
    }
}
