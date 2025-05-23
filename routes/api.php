<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProcessoController;
use App\Http\Controllers\API\CategoriaController;
use App\Http\Controllers\API\ContratoController;
use App\Http\Controllers\API\DetalhesDespesaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas protegidas por autenticação
Route::middleware('auth:sanctum')->group(function () {
    // Rotas de Processos
    Route::get('/processos', [ProcessoController::class, 'index']);
    Route::get('/processos/{id}', [ProcessoController::class, 'show']);
    Route::post('/processos', [ProcessoController::class, 'store']);
    Route::put('/processos/{id}', [ProcessoController::class, 'update']);
    Route::delete('/processos/{id}', [ProcessoController::class, 'destroy']);
    Route::get('/processos/totais', [ProcessoController::class, 'totais']);
    Route::get('/processos/vencimentos', [ProcessoController::class, 'vencimentos']);
    Route::get('/processos/contratos-por-ano', [ProcessoController::class, 'contratosPorAno']);

    // Rotas de Categorias
    Route::get('/categorias', [CategoriaController::class, 'index']);
    Route::get('/categorias/{id}', [CategoriaController::class, 'show']);
    Route::post('/categorias', [CategoriaController::class, 'store']);
    Route::put('/categorias/{id}', [CategoriaController::class, 'update']);
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']);

    // Rotas de Contratos
    Route::get('/contratos', [ContratoController::class, 'index']);
    Route::get('/contratos/{id}', [ContratoController::class, 'show']);
    Route::post('/contratos', [ContratoController::class, 'store']);
    Route::put('/contratos/{id}', [ContratoController::class, 'update']);
    Route::delete('/contratos/{id}', [ContratoController::class, 'destroy']);
    Route::get('/contratos/estatisticas', [ContratoController::class, 'estatisticas']);

    // Rotas de Detalhes de Despesas
    Route::get('/detalhes-despesas', [DetalhesDespesaController::class, 'index']);
    Route::get('/detalhes-despesas/{id}', [DetalhesDespesaController::class, 'show']);
    Route::post('/detalhes-despesas', [DetalhesDespesaController::class, 'store']);
    Route::put('/detalhes-despesas/{id}', [DetalhesDespesaController::class, 'update']);
    Route::delete('/detalhes-despesas/{id}', [DetalhesDespesaController::class, 'destroy']);
});
