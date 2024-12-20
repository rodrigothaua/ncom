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
        $totalProcessos = ProcessoCompra::count();
        $processosVencidos = ProcessoCompra::where('data_vencimento', '<', now())->count();
        $processosAtivos = ProcessoCompra::where('data_vencimento', '>=', now())->count();
        $processosPendentes = ProcessoCompra::whereBetween('data_vencimento', [now(), now()->addMonths(3)])->count();

        return view('dashboard', compact('totalProcessos', 'processosVencidos', 'processosAtivos', 'processosPendentes'));
    }
}
