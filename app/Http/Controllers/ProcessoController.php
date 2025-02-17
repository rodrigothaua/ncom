<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Processo;

class ProcessoController extends Controller
{
    public function index()
    {
        $processos = Processo::with('contratos')->paginate(10);
        return view('processos.index', compact('processos'));
    }

    public function create()
    {
        return view('processos.create');
    }

    public function store(Request $request)
    {
        //dd($request->all()); // Exibe todos os dados enviados no formulário
        // Limpar e formatar os valores removendo o símbolo de moeda
        $valor_consumo = $this->parseCurrency($request->valor_consumo);
        $valor_permanente = $this->parseCurrency($request->valor_permanente);
        $valor_servico = $this->parseCurrency($request->valor_servico);

        // Calcular o valor total
        $valor_total = $valor_consumo + $valor_permanente + $valor_servico;

        // Validação dos dados
        $validatedData = $request->validate([
            'numero_processo' => 'required|string|max:255',
            'descricao' => 'required|string|max:1000',
            'requisitante' => 'required|string|max:500',
            'data_inicio' => 'nullable|date',
            'data_vencimento' => 'nullable|date',
            'data_entrada' => 'nullable|date',
            'modalidade' => 'required|string',
            'procedimentos' => 'required|string',
            'contratos' => 'nullable|array',
            'contratos.*.numero_contrato' => 'required_with:contratos|string',
            'contratos.*.valor_contrato' => 'required_with:contratos|string',
            'contratos.*.data_inicial_contrato' => 'required_with:contratos|date',
            'contratos.*.data_final_contrato' => 'required_with:contratos|date',
            'contratos.*.obs' => 'nullable|string',
        ]);

        // Adicionar os valores calculados aos dados validados
        $validatedData['valor_consumo'] = $valor_consumo;
        $validatedData['valor_permanente'] = $valor_permanente;
        $validatedData['valor_servico'] = $valor_servico;
        $validatedData['valor_total'] = $valor_total;

        // Criar o processo no banco de dados
        $processo = Processo::create($validatedData);

        // Se houver contratos, salvar na tabela correta
        if ($request->has('contratos')) {
            $contratos = array_values($validatedData['contratos']); // 🔵 Corrige os índices dos contratos

            foreach ($contratos as $contrato) {
                Contrato::create([
                    'processo_id' => $processo->id,
                    'numero_contrato' => $contrato['numero_contrato'],
                    'valor_contrato' => $this->parseCurrency($contrato['valor_contrato']), // 🔹 Remove "R$" e converte
                    'data_inicial_contrato' => $contrato['data_inicial_contrato'],
                    'data_final_contrato' => $contrato['data_final_contrato'],
                    'obs' => $contrato['obs'] ?? null,
                ]);
            }
        }

        return redirect()->route('processos.index')->with('success', 'Processo criado com sucesso!');
    }

    private function parseCurrency($value)
    {
        // Remove tudo que não for número ou vírgula
        $value = preg_replace('/[^\d,]/', '', $value);
        
        // Converte a vírgula para ponto e converte para float
        return (float)str_replace(',', '.', $value);
    }

    public function edit($id)
    {
        $processo = Processo::with('contratos')->findOrFail($id);
        return view('processos.edit', compact('processo'));
    }

    public function update(Request $request, $id)
    {
        $processo = Processo::findOrFail($id);

        $validatedData = $request->validate([
            'numero_processo' => 'required|string|max:255|unique:processos,numero_processo,' . $id,
            'descricao' => 'required|string|max:1000',
            'requisitante' => 'required|string|max:255',
            'valor_consumo' => 'nullable|numeric',
            'valor_permanente' => 'nullable|numeric',
            'valor_servico' => 'nullable|numeric',
            'valor_total' => 'nullable|string',
            'data_inicio' => 'nullable|date',
            'data_vencimento' => 'nullable|date|after_or_equal:data_inicio',
            'data_entrada' => 'nullable|date',
        ]);

        // Atualiza os valores monetários, com a conversão correta
        $valor_consumo = $this->parseCurrency($request->valor_consumo);
        $valor_permanente = $this->parseCurrency($request->valor_permanente);
        $valor_servico = $this->parseCurrency($request->valor_servico);

        // Atualiza o valor total
        $validatedData['valor_total'] = $valor_consumo + $valor_permanente + $valor_servico;

        // Atualiza o processo
        $processo->update($validatedData);

        return redirect()->route('processos.index')->with('success', 'Processo atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Processo::findOrFail($id)->delete();
        return redirect()->route('processos.index')->with('success', 'Processo removido com sucesso!');
    }
}
