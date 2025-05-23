<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Categorias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    /**
     * Retorna lista de todas as categorias
     */
    public function index()
    {
        try {
            $categorias = Categorias::with(['processo', 'detalhesDespesa'])->get();
            return response()->json([
                'status' => true,
                'data' => $categorias
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao buscar categorias',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna uma categoria específica
     */
    public function show($id)
    {
        try {
            $categoria = Categorias::with(['processo', 'detalhesDespesa'])->find($id);
            
            if (!$categoria) {
                return response()->json([
                    'status' => false,
                    'message' => 'Categoria não encontrada'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $categoria
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao buscar categoria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cria uma nova categoria
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'processo_id' => 'required|exists:processos,id',
                'valor_consumo' => 'nullable|numeric',
                'valor_permanente' => 'nullable|numeric',
                'valor_servico' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            $categoria = Categorias::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Categoria criada com sucesso',
                'data' => $categoria
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao criar categoria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualiza uma categoria existente
     */
    public function update(Request $request, $id)
    {
        try {
            $categoria = Categorias::find($id);

            if (!$categoria) {
                return response()->json([
                    'status' => false,
                    'message' => 'Categoria não encontrada'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'processo_id' => 'exists:processos,id',
                'valor_consumo' => 'nullable|numeric',
                'valor_permanente' => 'nullable|numeric',
                'valor_servico' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            $categoria->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Categoria atualizada com sucesso',
                'data' => $categoria
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao atualizar categoria',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove uma categoria
     */
    public function destroy($id)
    {
        try {
            $categoria = Categorias::find($id);

            if (!$categoria) {
                return response()->json([
                    'status' => false,
                    'message' => 'Categoria não encontrada'
                ], 404);
            }

            $categoria->delete();

            return response()->json([
                'status' => true,
                'message' => 'Categoria removida com sucesso'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erro ao remover categoria',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
