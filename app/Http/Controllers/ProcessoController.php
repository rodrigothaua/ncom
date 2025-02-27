<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Processo;
use App\Models\ValoresProcesso;
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

    // Função para converter valores monetários (remove "R$" e converte para número)
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

    public function store(Request $request)
    {
        //dd($request->all()); // Exibe todos os dados enviados no formulário

        $validatedData = $request->validate([
            'numero_processo' => 'required|string',
            'descricao' => 'required|string',
            'requisitante' => 'required|string',
            'data_entrada' => 'required|date',
            'modalidade' => 'nullable|string',
            'procedimentos_auxiliares' => 'nullable|string',
            // Validação dos contratos
            'contratos' => 'nullable|array',
            'contratos.*.id' => 'nullable|exists:contratos,id',
            'contratos.*.numero_contrato' => 'required_with:contratos|string',
            'contratos.*.valor_contrato' => 'required_with:contratos|string',
            'contratos.*.data_inicial_contrato' => 'required_with:contratos|date',
            'contratos.*.data_final_contrato' => 'required_with:contratos|date',
            'contratos.*.observacao' => 'nullable|string', 
        ]);

        // Convertendo os valores monetários corretamente
        $valor_consumo = $this->parseCurrency($request->valor_consumo);
        $valor_permanente = $this->parseCurrency($request->valor_permanente);
        $valor_servico = $this->parseCurrency($request->valor_servico);
        // Calcula o valor total
        $valor_total = $valor_consumo + $valor_permanente + $valor_servico;

        // Adicionar os valores calculados aos dados validados
        $validatedData['valor_consumo'] = $valor_consumo;
        $validatedData['valor_permanente'] = $valor_permanente;
        $validatedData['valor_servico'] = $valor_servico;
        $validatedData['valor_total'] = $valor_total;

        // Criação do processo
        $processo = Processo::create($validatedData);

        ValoresProcesso::create([
            'processo_id' => $processo->id,
            'valor_consumo' => $this->parseCurrency($request->input('valor_consumo')),
            'valor_permanente' => $this->parseCurrency($request->input('valor_permanente')),
            'valor_servico' => $this->parseCurrency($request->input('valor_servico')),
        ]);

        // Salvando os contratos
        if ($request->has('contratos')) {
            foreach ($request->contratos as $contrato) {
                $valor_contrato = preg_replace('/[^0-9,]/', '', $contrato['valor_contrato']);
                $valor_contrato = str_replace(',', '.', $valor_contrato);
    
                if (isset($contrato['id'])) {
                    $processo->contratos()->where('id', $contrato['id'])->update([
                        'numero_contrato' => $contrato['numero_contrato'],
                        'valor_contrato' => $valor_contrato,
                        'data_inicial_contrato' => $contrato['data_inicial_contrato'],
                        'data_final_contrato' => $contrato['data_final_contrato'],
                        'observacoes' => $contrato['observacao'] ?? null,
                    ]);
                } else {
                    $processo->contratos()->create([
                        'numero_contrato' => $contrato['numero_contrato'],
                        'valor_contrato' => $valor_contrato,
                        'data_inicial_contrato' => $contrato['data_inicial_contrato'],
                        'data_final_contrato' => $contrato['data_final_contrato'],
                        'observacoes' => $contrato['observacao'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('processos.index')->with('success', 'Processo criado com sucesso!');
    }

    private function salvarValoresProcesso(Request $request, Processo $processo, $categoria)
    {
        $valor = $this->parseCurrency($request->input('valor_' . $categoria));
        $despesa = $request->input($categoria . '_despesa');

        if ($despesa && isset($despesa['numero_pa']) && isset($despesa['natureza_despesa'])) {
            ValoresProcesso::create([
                'processo_id' => $processo->id,
                'categoria' => $categoria,
                'valor' => $valor,
                'numero_pa' => $despesa['numero_pa'],
                'natureza_despesa' => $despesa['natureza_despesa'],
            ]);
        }
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
        $processo = Processo::findOrFail($id);

        // Excluir contratos relacionados
        $processo->contratos()->delete();

        // Excluir números de despesa relacionados
        $processo->valoresProcesso()->delete();

        // Excluir o processo
        $processo->delete();

        return redirect()->route('processos.index')->with('success', 'Processo removido com sucesso!');
    }
}
