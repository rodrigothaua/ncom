<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Services\ContratoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContratoController extends Controller
{
    protected $contratoService;

    public function __construct(ContratoService $contratoService)
    {
        $this->contratoService = $contratoService;
    }

    /**
     * Retorna lista de todos os contratos
     */
    public function index()
    {
        try {
            $contratos = Contrato::with('processo')->get();
            return response()->json([
                'status' => true,
                'data' => $contratos
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao buscar contratos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna um contrato específico
     */
    public function show($id)
    {
        try {
            $contrato = Contrato::with('processo')->find($id);
            
            if (!$contrato) {
                return response()->json([
                    'status' => false,
                    'message' => 'Contrato não encontrado'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $contrato
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao buscar contrato',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cria um novo contrato
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'processo_id' => 'required|exists:processos,id',
                'numero_contrato' => 'required|string',
                'valor_contrato' => 'required|numeric',
                'data_inicial_contrato' => 'required|date',
                'data_final_contrato' => 'required|date|after:data_inicial_contrato',
                'nome_empresa_contrato' => 'required|string',
                'cnpj_contrato' => 'required|string',
                'numero_telefone_contrato' => 'nullable|string',
                'observacoes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            $contrato = Contrato::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Contrato criado com sucesso',
                'data' => $contrato
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao criar contrato',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualiza um contrato existente
     */
    public function update(Request $request, $id)
    {
        try {
            $contrato = Contrato::find($id);

            if (!$contrato) {
                return response()->json([
                    'status' => false,
                    'message' => 'Contrato não encontrado'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'processo_id' => 'exists:processos,id',
                'numero_contrato' => 'string',
                'valor_contrato' => 'numeric',
                'data_inicial_contrato' => 'date',
                'data_final_contrato' => 'date|after:data_inicial_contrato',
                'nome_empresa_contrato' => 'string',
                'cnpj_contrato' => 'string',
                'numero_telefone_contrato' => 'nullable|string',
                'observacoes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            $contrato->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Contrato atualizado com sucesso',
                'data' => $contrato
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar contrato',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove um contrato
     */
    public function destroy($id)
    {
        try {
            $contrato = Contrato::find($id);

            if (!$contrato) {
                return response()->json([
                    'status' => false,
                    'message' => 'Contrato não encontrado'
                ], 404);
            }

            $contrato->delete();

            return response()->json([
                'status' => true,
                'message' => 'Contrato removido com sucesso'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao remover contrato',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna estatísticas dos contratos
     */
    public function estatisticas()
    {
        try {
            $totalAtual = $this->contratoService->calcularTotalContratosMesAtual();
            $totalAnterior = $this->contratoService->calcularTotalContratosMesAnterior();
            $crescimento = $this->contratoService->calcularPorcentagemCrescimento($totalAtual, $totalAnterior);

            return response()->json([
                'status' => true,
                'data' => [
                    'total_mes_atual' => $totalAtual,
                    'total_mes_anterior' => $totalAnterior,
                    'percentual_crescimento' => $crescimento
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao buscar estatísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
