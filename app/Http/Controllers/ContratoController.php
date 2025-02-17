<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contrato;
use App\Models\Processo;

class ContratoController extends Controller
{
    public function edit($id)
    {
        $contrato = Contrato::findOrFail($id);
        return view('contratos.edit', compact('contrato'));
    }

    public function update(Request $request, $id)
    {
        $contrato = Contrato::findOrFail($id);

        $validatedData = $request->validate([
            'numero_contrato' => 'required|string',
            'valor_contrato' => 'required|numeric',
            'data_inicial_contrato' => 'required|date',
            'data_final_contrato' => 'required|date',
            'obs' => 'nullable|string',
        ]);

        $contrato->update($validatedData);

        return redirect()->back()->with('success', 'Contrato atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $contrato = Contrato::findOrFail($id);
        $contrato->delete();

        return redirect()->back()->with('success', 'Contrato removido com sucesso!');
    }
}
