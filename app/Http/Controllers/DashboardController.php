<?php

namespace App\Http\Controllers;

use App\Models\User; // Modelo de usuários
use App\Models\ProcessoCompra; // Caso necessário
use Illuminate\Http\Request;

// Importação da classe Controller
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {  
        // Lógica para o dashboard
        $processos = ProcessoCompra::orderBy('data_vencimento', 'asc')->get();
    $totalProcessos = $processos->count();
    $totalConsumo = $processos->where('categoria', 'consumo')->count();
    $totalPermanente = $processos->where('categoria', 'permanente')->count();
    $totalServico = $processos->where('categoria', 'serviço')->count();

    $processosChartData = [
        $totalConsumo,
        $totalPermanente,
        $totalServico
    ];

    return view('welcome', compact(
        'processos',
        'totalProcessos',
        'totalConsumo',
        'totalPermanente',
        'totalServico',
        'processosChartData'
    ));
    }
}
