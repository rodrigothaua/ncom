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

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/create', [DashboardController::class, 'create'])->name('dashboard.create');

    // CRUD completo (Evita duplicação)
    Route::resource('processos', ProcessoController::class);

    // Página de registro de usuário
    Route::get('/usuarios/create', [RegisterController::class, 'showRegistrationForm'])->name('usuarios.create');
    Route::post('/usuarios/store', [RegisterController::class, 'register'])->name('usuarios.store');

    // Gestão de processos
    Route::prefix('processos')->name('processos.')->group(function () {
        Route::get('/', [ProcessoController::class, 'index'])->name('index'); // Lista de processos
        Route::get('/create', [ProcessoController::class, 'create'])->name('create'); // Página de criação
        Route::get('/{id}/edit', [ProcessoController::class, 'edit'])->name('edit'); // Página de edição
        Route::put('/{id}', [ProcessoController::class, 'update'])->name('update'); // Atualizar processo
        Route::delete('/{id}', [ProcessoController::class, 'destroy'])->name('destroy'); // Excluir processo
    });
});

// Rotas de autenticação
Auth::routes();

// Rotas de autenticação
require __DIR__.'/auth.php';
