<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProcessoController;
use App\Http\Controllers\Auth\RegisterController;

// Página inicial (Welcome)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rotas protegidas pelo middleware "auth"
Route::middleware(['auth'])->group(function () {

    // Dashboard principal (após login)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/create', [DashboardController::class, 'create'])->name('dashboard.create');

    // CRUD completo
    Route::resource('processos', ProcessoController::class);

    // Gestão de processos
    Route::prefix('processos')->name('processos.')->group(function () {
        Route::get('/', [ProcessoController::class, 'index'])->name('index'); // Lista de processos
        Route::get('/create', [ProcessoController::class, 'create'])->name('create'); // Página de criação
        Route::post('/store', [ProcessoController::class, 'store'])->name('store'); // Salvar novo processo
        Route::get('/{id}/edit', [ProcessoController::class, 'edit'])->name('processos.edit'); // Página de edição
        Route::put('/{id}', [ProcessoController::class, 'update'])->name('processos.update'); // Atualizar processo
        Route::delete('/{id}', [ProcessoController::class, 'destroy'])->name('processos.destroy'); // Excluir processo
    });
});

// Rotas de autenticação
Auth::routes();

// Página de registro de novo usuário
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Rotas de autenticação
require __DIR__.'/auth.php';
