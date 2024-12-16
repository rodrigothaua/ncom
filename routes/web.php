<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProcessoCompraController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Auth;

// Página inicial (redirect para o dashboard ou outra página desejada)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Rota para o Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Rotas de login e registro
// Rota para exibir o formulário de login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Rota para processar o login
Route::post('/login', [AuthController::class, 'login']);
// Rota para logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Rota para gerenciamento de processos de compras
Route::prefix('processos')->name('processos.')->middleware('auth')->group(function () {
    // Rota para visualizar todos os processos
    Route::get('/', [ProcessoCompraController::class, 'index'])->name('index');
    
    // Rota para exibir o formulário de criação de um novo processo
    Route::get('/create', [ProcessoCompraController::class, 'create'])->name('create');
    
    // Rota para salvar um novo processo
    Route::post('/store', [ProcessoCompraController::class, 'store'])->name('store');
    
    // Rota para editar um processo existente
    Route::get('/{processo}/edit', [ProcessoCompraController::class, 'edit'])->name('edit');
    
    // Rota para atualizar um processo existente
    Route::put('/{processo}', [ProcessoCompraController::class, 'update'])->name('update');
    
    // Rota para excluir um processo
    Route::delete('/{processo}', [ProcessoCompraController::class, 'destroy'])->name('destroy');
});

Route::resource('processos', ProcessoCompraController::class);
Route::get('dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Rota para exibição de erro 404 (não encontrado)
Route::fallback(function () {
    return view('errors.404');
});

Route::prefix('processos')->group(function () {
    Route::get('/', [ProcessoCompraController::class, 'index'])->name('processos.index');  // Exibe os processos
    Route::post('/', [ProcessoCompraController::class, 'store'])->name('processos.store');   // Armazena um novo processo
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
