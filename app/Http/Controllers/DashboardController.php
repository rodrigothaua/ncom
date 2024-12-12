<?php

namespace App\Http\Controllers;

use App\Models\User; // Modelo de usuários
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Número total de usuários
        $totalUsers = User::count();

        // Usuários cadastrados nos últimos 7 dias
        $recentUsers = User::where('created_at', '>=', now()->subDays(7))->count();

        // Dados para o gráfico de cadastros (últimos 7 dias)
        $userRegistrations = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->take(7)
            ->get();

        $labels = $userRegistrations->pluck('date');
        $data = $userRegistrations->pluck('count');

        return view('dashboard', compact('totalUsers', 'recentUsers', 'labels', 'data'));
    }
}
