<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Processo;

class ProcessoController extends Controller
{
    public function index()
    {
        $processos = Processo::all();
        return view('processos.index', compact('processos'));
    }

    public function create()
    {
        return view('processos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'categoria' => 'required|string',
            'valor_total' => 'required|numeric',
        ]);

        Processo::create($request->all());
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
            'nome' => 'required|string|max:255',
            'categoria' => 'required|string',
            'valor_total' => 'required|numeric',
        ]);

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
