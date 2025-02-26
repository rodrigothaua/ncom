<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumeroDespesa extends Model
{
    use HasFactory;

    protected $table = 'numero_despesa';

    protected $fillable = [
        'processo_id',
        'tipo',
        'numero_pa',
        'natureza_despesa',
        'valor'
    ];

    public function processo()
    {
        return $this->belongsTo(Processo::class, 'processo_id');
    }
}
