<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaNumero extends Model
{
    use HasFactory;

    protected $table = 'pa_numeros';

    protected $fillable = ['processo_id', 'tipo', 'numero_pa'];

    public function processo()
    {
        return $this->belongsTo(Processo::class);
    }
}
