<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessoPA extends Model
{
    use HasFactory;

    protected $fillable = [
        'processo_id', 
        'tipo', 
        'numero_pa', 
        'valor'];

    public function processo()
    {
        return $this->belongsTo(Processo::class, 'processo_id');
    }
}
