<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProcessoController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\RelatoriosController;
use Illuminate\Support\Facades\Auth;

// Página inicial (Welcome)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rota filtro
Route::post('/home/filter', [HomeController::class, 'filter'])->name('home.filter');

// Rotas protegidas pelo middleware "auth"
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/create', [DashboardController::class, 'create'])->name('dashboard.create');

    // Processos
    Route::resource('processos', ProcessoController::class);
    Route::prefix('processos')->name('processos.')->group(function () {
        Route::get('/', [ProcessoController::class, 'index'])->name('index');
        Route::get('/create', [ProcessoController::class, 'create'])->name('create');
        Route::get('/{id}', [ProcessoController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProcessoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProcessoController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProcessoController::class, 'destroy'])->name('destroy');
    });

    // Usuários
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/create', [RegisteredUserController::class, 'create'])->name('create');
        Route::post('/create', [RegisteredUserController::class, 'store']);
    });

    // Relatórios
    Route::prefix('relatorios')->name('relatorios.')->group(function () {
        // Página inicial dos relatórios
        Route::get('/', [RelatoriosController::class, 'index'])->name('index');
        
        // Filtro Geral
        Route::get('/filtro-geral/{tipo?}', [RelatoriosController::class, 'filtroGeral'])->name('filtro.geral');
        Route::post('/filtro-geral/buscar', [RelatoriosController::class, 'buscarFiltroGeral'])->name('filtro.geral.buscar');
        Route::post('/filtro-geral/pdf', [RelatoriosController::class, 'gerarPdfFiltroGeral'])->name('filtro.geral.pdf');
        
        // Contratos por Vencimento
        Route::get('/contratos-vencimento', [RelatoriosController::class, 'contratosPorVencimento'])->name('contratos.vencimento');
        Route::post('/contratos-vencimento/buscar', [RelatoriosController::class, 'buscarContratosPorVencimento'])->name('contratos.vencimento.buscar');
        Route::post('/contratos-vencimento/pdf', [RelatoriosController::class, 'gerarPdfContratosSelecionados'])->name('contratos.vencimento.pdf');
        Route::get('/contratos-vencimento/detalhes/{contrato}', [RelatoriosController::class, 'detalhesContratoVencimento'])->name('contratos.vencimento.detalhes');

        // Contratos por Valor
        Route::get('/contratos-valor', [RelatoriosController::class, 'contratosPorValor'])->name('contratos.valor');
        Route::post('/contratos-valor/buscar', [RelatoriosController::class, 'buscarContratosPorValor'])->name('contratos.valor.buscar');
        Route::post('/contratos-valor/pdf', [RelatoriosController::class, 'gerarPdfContratosSelecionados'])->name('contratos.valor.pdf');

        // Categorias por Processo
        Route::get('/categorias-processo', [RelatoriosController::class, 'categoriasPorProcesso'])->name('categorias.processo');
        Route::post('/categorias-processo/buscar', [RelatoriosController::class, 'buscarCategoriasPorProcesso'])->name('categorias.processo.buscar');
        Route::post('/categorias-processo/pdf', [RelatoriosController::class, 'gerarPdfProcessosSelecionados'])->name('categorias.processo.pdf');
    });
});

// Autenticação
Auth::routes();
require __DIR__.'/auth.php';
