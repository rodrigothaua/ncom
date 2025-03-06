@extends('layouts.app')

@section('title', 'NCON - Visão Geral')

@section('content')
    @include('layouts.navbar')

    <!-- Main Content -->
    <main>
        <section id="">
            <div class="container-fluid">
                <!-- Resumo de Contratos -->
                <div class="card">
                    <div class="card-header">
                        <b>RESUMO/CONTRATOS</b>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Total de Contratos -->
                            <div class="col-md-3">
                                <div class="card mb-3 bg-primary text-white">
                                    <div class="row g-0">
                                        <div class="col-md-3 icon">
                                            <i class="bi bi-file-earmark-medical"></i>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card-body">
                                                <h5 class="card-title">Total de Contratos: {{ $totalProcessos }}</h5>
                                                <hr>
                                                <p class="card-text"><small class="text-body-primary">100% Contratos ativos</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- VENCEM -30 DIAS -->
                                <div class="card mb-3 bg-danger text-white">
                                    <div class="row g-0">
                                        <div class="col-md-3 icon">
                                            <i class="bi bi-file-earmark"></i>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card-header">VENCEM(-30 DIAS)</div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $vencimentos['totalMenos30Dias'] }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- VENCEM 30 A 60 DIAS -->
                                <div class="card mb-3 text-white" style="background: #FF7701;">
                                    <div class="row g-0">
                                        <div class="col-md-3 icon">
                                            <i class="bi bi-file-earmark"></i>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card-header">VENCEM(30 A 60 DIAS)</div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $vencimentos['total30a60Dias'] }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- VENCEM 60 A 90 DIAS -->
                                <div class="card mb-3 bg-warning text-white">
                                    <div class="row g-0">
                                        <div class="col-md-3 icon">
                                            <i class="bi bi-file-earmark"></i>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card-header">VENCEM(60 A 90 DIAS)</div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $vencimentos['total60a90Dias'] }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- VENCEM 90 A 180 DIAS -->
                                <div class="card mb-3 bg-success text-white">
                                    <div class="row g-0">
                                        <div class="col-md-3 icon">
                                            <i class="bi bi-file-earmark"></i>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card-header">VENCEM(90 A 180 DIAS)</div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $vencimentos['total90a180Dias'] }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- VENCEM + 180 DIAS -->
                                <div class="card mb-3 bg-primary text-white">
                                    <div class="row g-0">
                                        <div class="col-md-3 icon">
                                            <i class="bi bi-file-earmark"></i>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card-header">VENCEM(+ 180 DIAS)</div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $vencimentos['totalMais180Dias'] }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Global -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header"><b><i class="bi bi-currency-dollar"></i> TOTAL GLOBAL DE CONTRATOS</b></div>
                                    <div class="card-body text-center">
                                        R$ {{ number_format($valorTotal, 2, ',', '.') }}
                                    </div>
                                </div><br>
                                <div class="card">
                                    <div class="card-header">
                                        <b><i class="bi bi-pie-chart-fill"></i> CONTRATOS POR CATEGORIAS</b></div>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="categoriasChart"
                                                data-consumo="{{ $totais['valorConsumo'] }}"
                                                data-permanente="{{ $totais['valorPermanente'] }}"
                                                data-servico="{{ $totais['valorServico'] }}">
                                        </canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Gráfico de Categoria -->
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-header"><b><i class="bi bi-pie-chart-fill"></i> Por Ano</b></div>
                                    <div class="card-body">
                                        <canvas id="contratosPorAnoChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>           
                </div>
            </div>
        </section>
    </main>
@endsection
