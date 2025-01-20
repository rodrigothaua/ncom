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
use App\Http\Controllers\WelcomeController;

// Página inicial
Route::get('/', [ProcessoController::class, 'index'])->name('welcome');

// Rotas para autenticação (Login, Registro, Logout)
Auth::routes();

// Login e Registro
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


    //Rota Charts
    Route::get('/processos-pie-chart-data', [ProcessosChartController::class, 'getPieChartData']);