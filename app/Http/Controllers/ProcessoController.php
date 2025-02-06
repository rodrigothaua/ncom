<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Processo;

class ProcessoController extends Controller
{
    public function index()
    {
        $processos = Processo::paginate(10); // Define 10 registros por página
        return view('processos.index', compact('processos'));
    }

    public function create()
    {
        return view('processos.create');
    }

    public function store(Request $request)
    {
        // Validação
        $validatedData = $request->validate([
            'numero_processo' => 'required|string|max:255',
            'descricao' => 'required|string|max:1000',
            'requisitante' => 'required|string|max:500',
            'categoria' => 'required|string',
            'valor_total' => 'nullable|numeric',
            'data_inicio' => 'nullable|date',
            'data_vencimento' => 'nullable|date',
        ]);

        // Criação do processo
        Processo::create($validatedData);

        // Redirecionar com sucesso
        return redirect()->route('processos.index')->with('success', 'Processo criado com sucesso!');
    }

    public function edit($id)
    {
        $processo = Processo::findOrFail($id);
        return view('processos.edit', compact('processo'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'numero_processo' => 'required|string|max:255',
            'descricao' => 'required|string|max:1000',
            'requisitante' => 'required|string|max:500',
            'categoria' => 'required|string',
            'valor_total' => 'nullable|numeric',
            'data_inicio' => 'nullable|date',
            'data_vencimento' => 'nullable|date|after_or_equal:data_inicio',
        ]);

        // Buscar o processo e atualizar os dados
        $processo = Processo::findOrFail($id);
        $processo->update($request->all());

        return redirect()->route('processos.index')->with('success', 'Processo atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $processo = Processo::findOrFail($id);
        $processo->delete();

        return redirect()->route('processos.index')->with('success', 'Processo removido com sucesso!');
    }
}
