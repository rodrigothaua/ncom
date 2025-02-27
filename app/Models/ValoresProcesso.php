<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValoresProcesso extends Model
{
    use HasFactory;

    protected $table = 'valores_processos';

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
}