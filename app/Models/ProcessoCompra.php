<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessoCompra extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_processo',
        'descricao',
        'data_vigente',
    ];
}

