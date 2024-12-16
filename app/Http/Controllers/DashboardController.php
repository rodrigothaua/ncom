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
        return view('dashboard');  // Ou qualquer outra view do dashboard
    }
}
