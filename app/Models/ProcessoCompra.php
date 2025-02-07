<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessoCompra extends Model
{
    use HasFactory;

    // Define explicitamente o nome da tabela
    protected $table = 'processo_compras';

    protected $fillable = [
        'numero_processo',
        'descricao',
        'requisitante',
        'categoria',
        'valor_total',
        'data_inicio',
        'data_vencimento',
    ];

    protected $casts = [
        'categoria' => 'array', // Permite armazenar múltiplos valores como JSON
    ];

    // Método para calcular o status com base na data de vencimento
    public function getStatusAttribute()
    {
        $currentDate = now();
        $dataVigente = \Carbon\Carbon::parse($this->data_vigente);
        $diffInMonths = $currentDate->diffInMonths($dataVigente);

        // Definir as cores de status de acordo com a data
        if ($dataVigente->isPast()) {
            return 'Vermelho'; // Vencido
        }

        if ($diffInMonths <= 3) {
            return 'Amarelo'; // 3 meses ou menos
        }

        if ($diffInMonths <= 6) {
            return 'Laranja'; // 6 meses ou menos
        }

        return 'Sem Cor'; // Mais de 1 ano
    }
}

