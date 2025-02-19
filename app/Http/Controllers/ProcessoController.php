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
            'data_entrada' => 'nullable|date',
            'modalidade' => 'required|string',
            'procedimentos' => 'required|string',
            // Validando os campos dos contratos
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

        // Criação do processo
        $processo = Processo::create($validatedData);

        // Verificação e salvamento dos contratos
        if ($request->has('contratos')) {
            foreach ($request->contratos as $contrato) {
                // Limpar o valor do contrato para garantir que esteja no formato correto
                $valor_contrato = preg_replace('/[^0-9,]/', '', $contrato['valor_contrato']); // Remover tudo, exceto números e vírgulas
                $valor_contrato = str_replace(',', '.', $valor_contrato); // Substituir vírgula por ponto decimal

                // Salvar o contrato
                $processo->contratos()->create([
                    'numero_contrato' => $contrato['numero_contrato'],
                    'valor_contrato' => $valor_contrato,
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
            'data_entrada' => 'nullable|date',

            // Validação dos contratos
            'contratos' => 'nullable|array',
            'contratos.*.id' => 'nullable|exists:contratos,id',
            'contratos.*.numero_contrato' => 'required_with:contratos|string',
            'contratos.*.valor_contrato' => 'required_with:contratos|string',
            'contratos.*.data_inicial_contrato' => 'required_with:contratos|date',
            'contratos.*.data_final_contrato' => 'required_with:contratos|date',
            'contratos.*.obs' => 'nullable|string',
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
                        'obs' => $contrato['obs'] ?? null,
                    ]);
                } else {
                    $processo->contratos()->create([
                        'numero_contrato' => $contrato['numero_contrato'],
                        'valor_contrato' => $valor_contrato,
                        'data_inicial_contrato' => $contrato['data_inicial_contrato'],
                        'data_final_contrato' => $contrato['data_final_contrato'],
                        'obs' => $contrato['obs'] ?? null,
                    ]);
                }
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
