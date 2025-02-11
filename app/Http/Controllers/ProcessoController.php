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
            'valor_consumo' => 'nullable|numeric',
            'valor_permanente' => 'nullable|numeric',
            'valor_servico' => 'nullable|numeric',
            'valor_total' => 'nullable|numeric',
            'data_inicio' => 'nullable|date',
            'data_vencimento' => 'nullable|date',
        ]);

        // Garantir que os valores vazios sejam convertidos para 0
        $validatedData['valor_consumo'] = $validatedData['valor_consumo'] ?? 0;
        $validatedData['valor_permanente'] = $validatedData['valor_permanente'] ?? 0;
        $validatedData['valor_servico'] = $validatedData['valor_servico'] ?? 0;

        // Calcula o valor total somando as categorias selecionadas
        $validatedData['valor_total'] = 
            ($request->valor_consumo ?? 0) + 
            ($request->valor_permanente ?? 0) + 
            ($request->valor_servico ?? 0);

        // Criar o processo
        Processo::create($validatedData);

        return redirect()->route('processos.index')->with('success', 'Processo criado com sucesso!');
    }

    public function edit($id)
    {
        $processo = Processo::findOrFail($id);
        return view('processos.edit', compact('processo'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'numero_processo' => 'required|string|max:255',
            'descricao' => 'required|string|max:1000',
            'requisitante' => 'required|string|max:500',
            'valor_consumo' => 'nullable|numeric',
            'valor_permanente' => 'nullable|numeric',
            'valor_servico' => 'nullable|numeric',
            'valor_total' => 'nullable|numeric',
            'data_inicio' => 'nullable|date',
            'data_vencimento' => 'nullable|date',
        ]);

        $validatedData['valor_consumo'] = $validatedData['valor_consumo'] ?? 0;
        $validatedData['valor_permanente'] = $validatedData['valor_permanente'] ?? 0;
        $validatedData['valor_servico'] = $validatedData['valor_servico'] ?? 0;

        // Calcular o valor total
        $validatedData['valor_total'] = 
            ($request->valor_consumo ?? 0) + 
            ($request->valor_permanente ?? 0) + 
            ($request->valor_servico ?? 0);

        // Atualizar processo
        $processo = Processo::findOrFail($id);
        $processo->update($validatedData);

        return redirect()->route('processos.index')->with('success', 'Processo atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $processo = Processo::findOrFail($id);
        $processo->delete();

        return redirect()->route('processos.index')->with('success', 'Processo removido com sucesso!');
    }
}
