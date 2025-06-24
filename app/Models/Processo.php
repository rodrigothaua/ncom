<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\UserTrackable;

class Processo extends Model
{
    use HasFactory, UserTrackable;

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

    public function detalhesDespesa()
    {
        return $this->hasOneThrough(
            DetalhesDespesa::class,
            Categorias::class,
            'processo_id', // Foreign key on categorias table...
            'categorias_id', // Foreign key on detalhes_despesa table...
            'id', // Local key on processos table...
            'id' // Local key on categorias table...
        );
    }
}
