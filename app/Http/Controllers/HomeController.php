<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Processo;

class HomeController extends Controller
{
    public function index()
    {
        // Calcula os totais
        $totalProcessos = Processo::count();
        $totalConsumo = Processo::where('categoria', 'consumo')->sum('valor_total');
        $totalPermanente = Processo::where('categoria', 'permanente')->sum('valor_total');
        $totalServico = Processo::where('categoria', 'serviço')->sum('valor_total');
        $valorTotal = Processo::sum('valor_total');

        // Retorna os dados para a view
        return view('welcome', compact('totalProcessos', 'totalConsumo', 'totalPermanente', 'totalServico', 'valorTotal'));
    }
}