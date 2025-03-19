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
        'nome_empresa_contrato',
        'cnpj_contrato',
        'numero_telefone_contrato',
        'valor_contrato',
        'data_inicial_contrato',
        'data_final_contrato',
        'observacoes',
    ];

    public function processo()
    {
        return $this->belongsTo(Processo::class);
    }
}
