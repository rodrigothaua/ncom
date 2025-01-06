<?php

use App\Http\Controllers\ProcessoCompraController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProcessosChartController;
use App\Http\Controllers\PerfilController;

// Rotas para autenticação (Login, Registro, Logout)
Auth::routes();

// Rota Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rotas protegidas por autenticação (somente para usuários autenticados)
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rotas para Processos
    Route::get('/processos', [ProcessoCompraController::class, 'index'])->name('processos.index');  // Lista todos os processos
    Route::get('/processos/create', [ProcessoCompraController::class, 'create'])->name('processos.create');  // Formulário de criação
    Route::post('/processos', [ProcessoCompraController::class, 'store'])->name('processos.store');  // Armazenar um novo processo
    Route::get('/processos/{id}/edit', [ProcessoCompraController::class, 'edit'])->name('processos.edit');  // Editar processo
    Route::put('/processos/{id}', [ProcessoCompraController::class, 'update'])->name('processos.update');  // Atualizar processo
    Route::delete('/processos/{id}', [ProcessoCompraController::class, 'destroy'])->name('processos.destroy');  // Deletar processo

    //Rota Perfil
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil');
    Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('perfil.editar');
    Route::put('/perfil/atualizar', [PerfilController::class, 'update'])->name('perfil.atualizar');

});

// Página inicial (pública) - sem necessidade de autenticação
Route::get('/', function () {
    return view('welcome');
});

    //Rota Charts
    Route::get('/processos-pie-chart-data', [ProcessosChartController::class, 'getPieChartData']);