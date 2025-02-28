@extends('layouts.app')

@section('title', 'Dashboard - SIGECOM')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
<div class="container-fluid">
    <!-- Conteúdo principal -->
    <h1 class="my-4">Bem-vindo, {{ auth()->user()->name ?? 'Usuário' }}!</h1>

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total de Processos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProcessos }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Valor Consumo</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalValorConsumo }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Valor Permanente</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalValorPermanente  }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Valor Serviço</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalValorServico }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
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
