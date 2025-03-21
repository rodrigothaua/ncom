<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $table = 'contratos';

    protected $fillable = [
        'processo_id',
        'numero_contrato',
        'valor_contrato',
        'data_inicial_contrato',
        'data_final_contrato',
        'observacoes',
        'nome_empresa_contrato',
        'cnpj_contrato',
        'numero_telefone_contrato'
    ];

    public function processo()
    {
        return $this->belongsTo(Processo::class);
    }

    public function detalhesDespesa()
    {
        return $this->hasOneThrough(
            DetalhesDespesa::class,
            Processo::class,
            'id', // Foreign key on processos table...
            'categorias_id', // Foreign key on detalhes_despesa table...
            'processo_id', // Local key on contratos table...
            'id' // Local key on processos table...
        );
    }
}
