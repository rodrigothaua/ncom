@extends('layouts.app')

@section('title', 'Dashboard - NCOM')

@section('content')

    <!-- Conteúdo principal -->
    <h1 class="my-4">Bem-vindo, {{ auth()->user()->name ?? 'Usuário' }}!</h1>

    <div class="row">

        <!-- Card: Total de Processos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total de Processos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProcessos ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Processos Vencidos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Processos Vencidos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $processosVencidos ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Processos Ativos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Processos Ativos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $processosAtivos ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Processos Pendentes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Processos Pendentes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $processosPendentes ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Gráfico de Resumo de Processos -->
    <div class="row">
        <div class="col-lg-3 col-md-12">
            <strong>Resumo de processos</strong>
            <canvas id="processosPieChart"></canvas>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('processosPieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Vencidos', 'Ativos', 'Pendentes'],
                    datasets: [{
                        data: [{{ $processosVencidos ?? 0 }}, {{ $processosAtivos ?? 0 }}, {{ $processosPendentes ?? 0 }}],
                        backgroundColor: ['#dc3545', '#28a745', '#ffc107'],
                    }]
                }
            });
        });
    </script>
@endpush
