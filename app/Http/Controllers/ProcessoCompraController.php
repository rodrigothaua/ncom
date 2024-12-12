<?php

namespace App\Http\Controllers;

use App\Models\ProcessoCompra;
use Illuminate\Http\Request;

class ProcessoCompraController extends Controller
{
    //Gerenciador de processos
    public function index()
    {
        $processos = ProcessoCompra::all();

        // Calcular status com base na data vigente
        foreach ($processos as $processo) {
            $diff = now()->diffInMonths($processo->data_vigente, false);

            if ($diff > 12) {
                $processo->status = 'green';
            } elseif ($diff > 6) {
                $processo->status = 'yellow';
            } else {
                $processo->status = 'red';
            }
        }

        return view('processos.index', compact('processos'));
    }

    public function create()
    {
        return view('processos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_processo' => 'required|unique:processos_compras',
            'descricao' => 'required',
            'data_vigente' => 'required|date',
        ]);

        ProcessoCompra::create($request->all());

        return redirect()->route('processos.index')->with('success', 'Processo cadastrado com sucesso!');
    }
}
