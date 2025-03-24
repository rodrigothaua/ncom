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

    // CRUD completo (Evita duplicação)
    Route::resource('processos', ProcessoController::class);

    // Gestão de processos
    Route::prefix('processos')->name('processos.')->group(function () {
        Route::get('/', [ProcessoController::class, 'index'])->name('index');
        Route::get('/create', [ProcessoController::class, 'create'])->name('create');
        Route::get('/{id}', [ProcessoController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProcessoController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProcessoController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProcessoController::class, 'destroy'])->name('destroy');
    });

    //Cadastro de usuários
    Route::prefix('usuarios')->name('usuarios.')->group(function () {
        Route::get('/create', [RegisteredUserController::class, 'create'])->name('create');
        Route::post('/create', [RegisteredUserController::class, 'store']);
    });

    // Relatórios
    Route::prefix('relatorios')->name('relatorios.')->group(function () {
        // Rotas principais
        Route::get('/', [RelatoriosController::class, 'index'])->name('index');
        Route::get('/filtro-geral', [RelatoriosController::class, 'filtroGeral'])->name('filtro.geral');
        Route::get('/contratos-vencimento', [RelatoriosController::class, 'contratosPorVencimento'])->name('contratos.vencimento');
        Route::get('/contratos-valor', [RelatoriosController::class, 'contratosPorValor'])->name('contratos.valor');
        Route::get('/categorias-processo', [RelatoriosController::class, 'categoriasPorProcesso'])->name('categorias.processo');
        
        // Rotas de busca
        Route::post('/filtro-geral/buscar', [RelatoriosController::class, 'buscarFiltroGeral'])->name('filtro.geral.buscar');
        Route::post('/contratos-vencimento/buscar', [RelatoriosController::class, 'buscarContratosPorVencimento'])->name('contratos.vencimento.buscar');
        Route::post('/contratos-valor/buscar', [RelatoriosController::class, 'buscarContratosPorValor'])->name('contratos.valor.buscar');
        Route::post('/categorias-processo/buscar', [RelatoriosController::class, 'buscarCategoriasPorProcesso'])->name('categorias.processo.buscar');

        // Rotas para PDFs
        Route::post('/contratos-vencimento/pdf', [RelatoriosController::class, 'gerarPdfContratosSelecionados'])->name('contratos.vencimento.pdf');
        Route::post('/contratos-valor/pdf', [RelatoriosController::class, 'gerarPdfContratosSelecionados'])->name('contratos.valor.pdf');
        Route::post('/categorias-processo/pdf', [RelatoriosController::class, 'gerarPdfProcessosSelecionados'])->name('categorias.processo.pdf');
    });    
});

// Rotas de autenticação
Auth::routes();

// Rotas de autenticação
require __DIR__.'/auth.php';
