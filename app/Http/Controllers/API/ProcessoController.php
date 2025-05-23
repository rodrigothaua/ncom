<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Processo;
use App\Services\ProcessoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProcessoController extends Controller
{
    protected $processoService;

    public function __construct(ProcessoService $processoService)
    {
        $this->processoService = $processoService;
    }

    /**
     * Retorna lista de todos os processos
     */
    public function index()
    {
        try {
            $processos = $this->processoService->getProcessos();
            return response()->json([
                'status' => true,
                'data' => $processos
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao buscar processos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna dados de um processo específico
     */
    public function show($id)
    {
        try {
            $processo = Processo::with(['categorias', 'contratos', 'detalhesDespesa'])->find($id);
            
            if (!$processo) {
                return response()->json([
                    'status' => false,
                    'message' => 'Processo não encontrado'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $processo
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao buscar processo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cria um novo processo
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'numero_processo' => 'required|unique:processos',
                'descricao' => 'required',
                'requisitante' => 'required',
                'data_entrada' => 'required|date',
                'modalidade' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            $processo = Processo::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Processo criado com sucesso',
                'data' => $processo
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao criar processo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualiza um processo existente
     */
    public function update(Request $request, $id)
    {
        try {
            $processo = Processo::find($id);

            if (!$processo) {
                return response()->json([
                    'status' => false,
                    'message' => 'Processo não encontrado'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'numero_processo' => 'unique:processos,numero_processo,' . $id,
                'descricao' => 'required',
                'requisitante' => 'required',
                'data_entrada' => 'required|date',
                'modalidade' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            $processo->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Processo atualizado com sucesso',
                'data' => $processo
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar processo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove um processo
     */
    public function destroy($id)
    {
        try {
            $processo = Processo::find($id);

            if (!$processo) {
                return response()->json([
                    'status' => false,
                    'message' => 'Processo não encontrado'
                ], 404);
            }

            $processo->delete();

            return response()->json([
                'status' => true,
                'message' => 'Processo removido com sucesso'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao remover processo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna os totais dos processos
     */
    public function totais()
    {
        try {
            $totais = $this->processoService->getTotais();
            return response()->json([
                'status' => true,
                'data' => $totais
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao buscar totais',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna os vencimentos dos contratos
     */
    public function vencimentos()
    {
        try {
            $vencimentos = $this->processoService->getVencimentos();
            return response()->json([
                'status' => true,
                'data' => $vencimentos
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao buscar vencimentos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna estatísticas de contratos por ano
     */
    public function contratosPorAno()
    {
        try {
            $stats = $this->processoService->getContratosPorAno();
            return response()->json([
                'status' => true,
                'data' => $stats
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
