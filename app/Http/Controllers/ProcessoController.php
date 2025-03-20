<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Processo;
use App\Models\Categorias;
use App\Models\DetalhesDespesa;
use App\Models\Contrato;


class ProcessoController extends Controller
{
    public function index()
    {
        $processos = Processo::with(['contratos', 'categorias', 'categorias.detalhesDespesa'])->paginate(10);
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
            'numero_processo' => [
                'required',
                'string',
                'regex:/^\d{4}\.\d{6}\/\d{4}-\d{2}$/',
                'unique:processos,numero_processo'
            ],
            'consumo_despesa.numero_pa' => [
                'nullable',
                'regex:/^\d{2}\.\d{3}\.\d{3}\.\d{3}$/'
            ],
            'consumo_despesa.natureza_despesa' => [
                'nullable',
                'regex:/^\d\.\d\.\d{2}\.\d{2}$/'
            ],
            'permanente_despesa.numero_pa' => [
                'nullable',
                'regex:/^\d{2}\.\d{3}\.\d{3}\.\d{3}$/'
            ],
            'permanente_despesa.natureza_despesa' => [
                'nullable',
                'regex:/^\d\.\d\.\d{2}\.\d{2}$/'
            ],
            'servico_despesa.numero_pa' => [
                'nullable',
                'regex:/^\d{2}\.\d{3}\.\d{3}\.\d{3}$/'
            ],
            'servico_despesa.natureza_despesa' => [
                'nullable',
                'regex:/^\d\.\d\.\d{2}\.\d{2}$/'
            ],
            'descricao' => 'required|string',
            'requisitante' => 'required|string',
            'data_entrada' => 'required|date',
            'modalidade' => 'nullable|string',
            'procedimentos_auxiliares' => 'nullable|string',
            // Validação dos contratos
            'contratos' => 'nullable|array',
            'contratos.*.id' => 'nullable|exists:contratos,id',
            'contratos.*.numero_contrato' => 'required_with:contratos|string',
            'contratos.*.nome_empresa_contrato' => 'required_with:contratos|string',
            'contratos.*.cnpj_contrato' => 'required_with:contratos|string',
            'contratos.*.numero_telefone_contrato' => 'required_with:contratos|string',
            'contratos.*.valor_contrato' => 'required_with:contratos|string',
            'contratos.*.data_inicial_contrato' => 'required_with:contratos|date',
            'contratos.*.data_final_contrato' => 'required_with:contratos|date',
            'contratos.*.observacoes' => 'nullable|string', 
        ]);

        // Convertendo os valores monetários corretamente
        $valor_consumo = $this->parseCurrency($request->valor_consumo);
        $valor_permanente = $this->parseCurrency($request->valor_permanente);
        $valor_servico = $this->parseCurrency($request->valor_servico);

        // Calcula o valor total
        $valor_total = $valor_consumo + $valor_permanente + $valor_servico;

        // Adicionar os valores calculados aos dados validados
        $validatedData['valor_total'] = $valor_total;

        // Criação do processo
        $processo = Processo::create($validatedData);

        // Criação das categorias
        $categorias = Categorias::create([
            'processo_id' => $processo->id,
            'valor_consumo' => $valor_consumo,
            'valor_permanente' => $valor_permanente,
            'valor_servico' => $valor_servico,
        ]);

        // Criação dos detalhes das despesas
        $despesaConsumo = $request->input('consumo_despesa');
        $despesaPermanente = $request->input('permanente_despesa');
        $despesaServico = $request->input('servico_despesa');

        DetalhesDespesa::create([
            'categorias_id' => $categorias->id,
            'pa_consumo' => $despesaConsumo['numero_pa'] ?? null,
            'nd_consumo' => $despesaConsumo['natureza_despesa'] ?? null,
            'pa_permanente' => $despesaPermanente['numero_pa'] ?? null,
            'nd_permanente' => $despesaPermanente['natureza_despesa'] ?? null,
            'pa_servico' => $despesaServico['numero_pa'] ?? null,
            'nd_servico' => $despesaServico['natureza_despesa'] ?? null,
        ]);

        // Salvando os contratos
        if ($request->has('contratos')) {
            foreach ($request->contratos as $contrato) {
                $valor_contrato = preg_replace('/[^0-9,]/', '', $contrato['valor_contrato']);
                $valor_contrato = str_replace(',', '.', $valor_contrato);
    
                if (isset($contrato['id'])) {
                    $processo->contratos()->where('id', $contrato['id'])->update([
                        'numero_contrato' => $contrato['numero_contrato'],
                        'nome_empresa_contrato' => $contrato['nome_empresa_contrato'],
                        'cnpj_contrato' => $contrato['cnpj_contrato'],
                        'numero_telefone_contrato' => $contrato['numero_telefone_contrato'],
                        'valor_contrato' => $valor_contrato,
                        'data_inicial_contrato' => $contrato['data_inicial_contrato'],
                        'data_final_contrato' => $contrato['data_final_contrato'],
                        'observacoes' => $contrato['observacao'] ?? null,
                    ]);
                } else {
                    $processo->contratos()->create([
                        'numero_contrato' => $contrato['numero_contrato'],
                        'nome_empresa_contrato' => $contrato['nome_empresa_contrato'],
                        'cnpj_contrato' => $contrato['cnpj_contrato'],
                        'numero_telefone_contrato' => $contrato['numero_telefone_contrato'],
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
            'numero_processo' => [
                'required',
                'string',
                'regex:/^\d{4}\.\d{6}\/\d{4}-\d{2}$/',
                'unique:processos,numero_processo,' . $id
            ],
            'descricao' => 'required|string|max:1000',
            'requisitante' => 'required|string|max:255',
            'valor_consumo' => 'nullable|numeric',
            'valor_permanente' => 'nullable|numeric',
            'valor_servico' => 'nullable|numeric',
            'valor_total' => 'nullable|string',
            'data_entrada' => 'nullable|date',
            'contratos' => 'nullable|array',
            'contratos.*.id' => 'nullable|exists:contratos,id',
            'contratos.*.numero_contrato' => 'required_with:contratos|string',
            'contratos.*.nome_empresa_contrato' => 'required_with:contratos|string',
            'contratos.*.cnpj_contrato' => 'required_with:contratos|string',
            'contratos.*.numero_telefone_contrato' => 'required_with:contratos|string',
            'contratos.*.valor_contrato' => 'required_with:contratos|string',
            'contratos.*.data_inicial_contrato' => 'required_with:contratos|date',
            'contratos.*.data_final_contrato' => 'required_with:contratos|date',
            'contratos.*.observacoes' => 'nullable|string',
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
                        'nome_empresa_contrato' => $contrato['nome_empresa_contrato'],
                        'cnpj_contrato' => $contrato['cnpj_contrato'],
                        'numero_telefone_contrato' => $contrato['numero_telefone_contrato'],
                        'valor_contrato' => $valor_contrato,
                        'data_inicial_contrato' => $contrato['data_inicial_contrato'],
                        'data_final_contrato' => $contrato['data_final_contrato'],
                        'observacoes' => $contrato['observacao'] ?? null,
                    ]);
                } else {
                    $processo->contratos()->create([
                        'numero_contrato' => $contrato['numero_contrato'],
                        'nome_empresa_contrato' => $contrato['nome_empresa_contrato'],
                        'cnpj_contrato' => $contrato['cnpj_contrato'],
                        'numero_telefone_contrato' => $contrato['numero_telefone_contrato'],
                        'valor_contrato' => $valor_contrato,
                        'data_inicial_contrato' => $contrato['data_inicial_contrato'],
                        'data_final_contrato' => $contrato['data_final_contrato'],
                        'observacoes' => $contrato['observacao'] ?? null,
                    ]);
                }
            }
        }

        // Atualização das categorias
        if ($processo->categorias) {
            $processo->categorias->update([
                'valor_consumo' => $valor_consumo,
                'valor_permanente' => $valor_permanente,
                'valor_servico' => $valor_servico,
            ]);
        } else {
            Categorias::create([
                'processo_id' => $processo->id,
                'valor_consumo' => $valor_consumo,
                'valor_permanente' => $valor_permanente,
                'valor_servico' => $valor_servico,
            ]);
        }

        // Atualização dos detalhes das despesas
        if ($processo->categorias && $processo->categorias->detalhesDespesa) {
            $despesaConsumo = $request->input('consumo_despesa');
            $despesaPermanente = $request->input('permanente_despesa');
            $despesaServico = $request->input('servico_despesa');

            $processo->categorias->detalhesDespesa->update([
                'pa_consumo' => $despesaConsumo['numero_pa'] ?? null,
                'nd_consumo' => $despesaConsumo['natureza_despesa'] ?? null,
                'pa_permanente' => $despesaPermanente['numero_pa'] ?? null,
                'nd_permanente' => $despesaPermanente['natureza_despesa'] ?? null,
                'pa_servico' => $despesaServico['numero_pa'] ?? null,
                'nd_servico' => $despesaServico['natureza_despesa'] ?? null,
            ]);
        } else {
            $despesaConsumo = $request->input('consumo_despesa');
            $despesaPermanente = $request->input('permanente_despesa');
            $despesaServico = $request->input('servico_despesa');

            DetalhesDespesa::create([
                'categorias_id' => $processo->categorias->id,
                'pa_consumo' => $despesaConsumo['numero_pa'] ?? null,
                'nd_consumo' => $despesaConsumo['natureza_despesa'] ?? null,
                'pa_permanente' => $despesaPermanente['numero_pa'] ?? null,
                'nd_permanente' => $despesaPermanente['natureza_despesa'] ?? null,
                'pa_servico' => $despesaServico['numero_pa'] ?? null,
                'nd_servico' => $despesaServico['natureza_despesa'] ?? null,
            ]);
        }

        return redirect()->route('processos.index')->with('success', 'Processo atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $processo = Processo::findOrFail($id);

        // Excluir contratos relacionados
        $processo->contratos()->delete();

        // Excluir detalhes das despesas relacionados
        if ($processo->categorias && $processo->categorias->detalhesDespesa) {
            $processo->categorias->detalhesDespesa->delete();
        }

        // Excluir categorias relacionadas
        if ($processo->categorias) {
            $processo->categorias()->delete();
        }

        // Excluir o processo
        $processo->delete();

        return redirect()->route('processos.index')->with('success', 'Processo removido com sucesso!');
    }
}
