<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Processo;

class DashboardController extends Controller
{
    public function index()
    {
        // Calcula o total de processos
        $totalProcessos = Processo::count();

        // Calcula o total de consumo (só um exemplo, ajuste conforme seu banco)
        $totalConsumo = Processo::where('categoria', 'consumo')->sum('valor_total');

        // Calcula o total de permanente
        $totalPermanente = Processo::where('categoria', 'permanente')->sum('valor_total');

        // Calcula o total de serviço
        $totalServico = Processo::where('categoria', 'serviço')->sum('valor_total');

        // Passa as variáveis para a view
        return view('dashboard.index', compact('totalProcessos', 'totalConsumo', 'totalPermanente', 'totalServico'));
    }

    public function create()
    {
        return view('dashboard.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'categoria' => 'required|string',
            'valor_total' => 'required|numeric',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

        Processo::create($validated);

        return redirect()->route('dashboard.index')->with('success', 'Processo criado com sucesso!');
    }
}
