<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Processo;
use App\Models\ProcessoPA;
use App\Models\Contrato;

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

        $validatedData = $request->validate([
            'numero_processo' => 'required|string|max:255|unique:processos,numero_processo,',
            'descricao' => 'required|string|max:1000',
            'requisitante' => 'required|string|max:255',
            'valor_consumo' => 'nullable|numeric',
            'valor_permanente' => 'nullable|numeric',
            'valor_servico' => 'nullable|numeric',
            'valor_total' => 'nullable|numeric', // Alterado para numeric
            'data_entrada' => 'nullable|date',
        ]);

        // Convertendo os valores monetários corretamente
        $valor_consumo = $this->parseCurrency($request->valor_consumo);
        $valor_permanente = $this->parseCurrency($request->valor_permanente);
        $valor_servico = $this->parseCurrency($request->valor_servico);
        // Calcula o valor total
        $valor_total = $valor_consumo + $valor_permanente + $valor_servico;

        // Criando o Processo
        $processo = Processo::create([
            'numero_processo' => $validatedData['numero_processo'],
            'requisitante' => $validatedData['requisitante'],
            'descricao' => $validatedData['descricao'],
            'data_entrada' => $validatedData['data_entrada'],
            'valor_consumo' => $valor_consumo,
            'valor_permanente' => $valor_permanente,
            'valor_servico' => $valor_servico,
            'valor_total' => $valor_total,

            //Salva os campos PA e Selects
            //'pa_consumo' => $pa_consumo,
            //'pa_permanente' => $pa_permanente,
            //'pa_servico' => $pa_servico,
            //'select_consumo' => $select_consumo,
            //'select_permanente' => $select_permanente,
            //'select_servico' => $select_servico,
        ]);

        return redirect()->route('processos.index')->with('success', 'Processo criado com sucesso!');
    }

    private function parseCurrency($value)
    {
        // Remove tudo que não for número ou vírgula
        if ($value) {
            // Remove "R$" e espaços
            $value = preg_replace('/[^\d,]/', '', $value);
            // Substitui vírgula decimal por ponto
            return (float) str_replace(',', '.', $value);
        }
        return 0;
        
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
            'data_entrada' => 'nullable|date',

            // Validação dos contratos
            'contratos' => 'nullable|array',
            'contratos.*.id' => 'nullable|exists:contratos,id',
            'contratos.*.numero_contrato' => 'required_with:contratos|string',
            'contratos.*.valor_contrato' => 'required_with:contratos|string',
            'contratos.*.data_inicial_contrato' => 'required_with:contratos|date',
            'contratos.*.data_final_contrato' => 'required_with:contratos|date',
            'contratos.*.observacao' => 'nullable|string',
            'contratos.*.modalidade' => 'nullable|string',
            'contratos.*.procedimentos_auxiliares' => 'nullable|string',

            // Validação de PAs
            'pa_numeros' => 'required|array',
            'pa_numeros.*.tipo' => 'required|string',
            'pa_numeros.*.numero_pa' => 'required|string',
        ]);

        // Atualiza os valores monetários, com a conversão correta
        $valor_consumo = $this->parseCurrency($request->valor_consumo);
        $valor_permanente = $this->parseCurrency($request->valor_permanente);
        $valor_servico = $this->parseCurrency($request->valor_servico);

        // Atualiza o valor total
        $validatedData['valor_total'] = $valor_consumo + $valor_permanente + $valor_servico;

        // Atualiza o processo
        $processo->update($validatedData);

        // Atualização dos contratos
        if ($request->has('contratos')) {
            $ids_contratos_enviados = collect($request->contratos)->pluck('id')->filter()->toArray();

            // Excluir contratos que não estão mais no formulário
            $processo->contratos()->whereNotIn('id', $ids_contratos_enviados)->delete();

            foreach ($request->contratos as $contrato) {
                $valor_contrato = preg_replace('/[^0-9,]/', '', $contrato['valor_contrato']);
                $valor_contrato = str_replace(',', '.', $valor_contrato);

                if (isset($contrato['id'])) {
                    $processo->contratos()->where('id', $contrato['id'])->update([
                        'numero_contrato' => $contrato['numero_contrato'],
                        'valor_contrato' => $valor_contrato,
                        'data_inicial_contrato' => $contrato['data_inicial_contrato'],
                        'data_final_contrato' => $contrato['data_final_contrato'],
                        'observacao' => $contrato['observacao'] ?? null,
                    ]);
                } else {
                    $processo->contratos()->create([
                        'numero_contrato' => $contrato['numero_contrato'],
                        'valor_contrato' => $valor_contrato,
                        'data_inicial_contrato' => $contrato['data_inicial_contrato'],
                        'data_final_contrato' => $contrato['data_final_contrato'],
                        'observacao' => $contrato['observacao'] ?? null,
                    ]);
                }
            }
        }

        $processo->paProcessos()->delete();

        // Atualização de PAs
        if ($request->has('pa_numeros')) {
            // Sincroniza os números de PA
            $processo->paProcessos()->sync([]); // Limpa os PAs existentes

            foreach ($request->pa_numeros as $pa) {
                $processo->paProcessos()->create([
                    'tipo' => $pa['tipo'],
                    'numero_pa' => $pa['numero_pa'],
                ]);
            }
        }

        return redirect()->route('processos.index')->with('success', 'Processo atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Processo::findOrFail($id)->delete();
        return redirect()->route('processos.index')->with('success', 'Processo removido com sucesso!');
    }
}
