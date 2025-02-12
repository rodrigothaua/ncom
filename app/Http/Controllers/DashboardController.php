<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Processo;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Obtém o usuário autenticado

        // Calcula o total de processos
        $totalProcessos = Processo::count();

        // Calcula o total de consumo (só um exemplo, ajuste conforme seu banco)
        $totalConsumo = Processo::sum('valor_consumo');

        // Calcula o total de permanente
        $totalPermanente = Processo::sum('valor_permanente');

        // Calcula o total de serviço
        $totalServico = Processo::sum('valor_servico');

        // Passa as variáveis para a view
        return view('dashboard.index', compact(
            'user',
            'totalProcessos', 
            'totalConsumo', 
            'totalPermanente', 
            'totalServico'));
    }

    public function create()
    {
        return view('dashboard.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_processo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'valor_consumo' => 'nullable|numeric',
            'valor_permanente' => 'nullable|numeric',
            'valor_servico' => 'nullable|numeric',
            'data_inicio' => 'nullable|date',
            'data_vencimento' => 'nullable|date|after_or_equal:data_inicio',
        ]);

        Processo::create($validated);

        return redirect()->route('dashboard.index')->with('success', 'Processo criado com sucesso!');
    }
}
