<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DetalhesDespesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetalhesDespesaController extends Controller
{
    /**
     * Retorna lista de todos os detalhes de despesas
     */
    public function index()
    {
        try {
            $detalhes = DetalhesDespesa::with('categorias.processo')->get();
            return response()->json([
                'status' => true,
                'data' => $detalhes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao buscar detalhes de despesas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna um detalhe de despesa específico
     */
    public function show($id)
    {
        try {
            $detalhe = DetalhesDespesa::with('categorias.processo')->find($id);
            
            if (!$detalhe) {
                return response()->json([
                    'status' => false,
                    'message' => 'Detalhe de despesa não encontrado'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $detalhe
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao buscar detalhe de despesa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cria um novo detalhe de despesa
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'categorias_id' => 'required|exists:categorias,id',
                'pa_consumo' => 'nullable|string',
                'pa_permanente' => 'nullable|string',
                'pa_servico' => 'nullable|string',
                'nd_consumo' => 'nullable|string',
                'nd_permanente' => 'nullable|string',
                'nd_servico' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            $detalhe = DetalhesDespesa::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Detalhe de despesa criado com sucesso',
                'data' => $detalhe
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao criar detalhe de despesa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualiza um detalhe de despesa existente
     */
    public function update(Request $request, $id)
    {
        try {
            $detalhe = DetalhesDespesa::find($id);

            if (!$detalhe) {
                return response()->json([
                    'status' => false,
                    'message' => 'Detalhe de despesa não encontrado'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'categorias_id' => 'exists:categorias,id',
                'pa_consumo' => 'nullable|string',
                'pa_permanente' => 'nullable|string',
                'pa_servico' => 'nullable|string',
                'nd_consumo' => 'nullable|string',
                'nd_permanente' => 'nullable|string',
                'nd_servico' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            $detalhe->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Detalhe de despesa atualizado com sucesso',
                'data' => $detalhe
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar detalhe de despesa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove um detalhe de despesa
     */
    public function destroy($id)
    {
        try {
            $detalhe = DetalhesDespesa::find($id);

            if (!$detalhe) {
                return response()->json([
                    'status' => false,
                    'message' => 'Detalhe de despesa não encontrado'
                ], 404);
            }

            $detalhe->delete();

            return response()->json([
                'status' => true,
                'message' => 'Detalhe de despesa removido com sucesso'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao remover detalhe de despesa',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
