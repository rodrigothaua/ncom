@extends('layouts.app')

@section('title', 'NCON - Visão Geral')

@section('content')
    @include('layouts.navbar')

    <!-- Main Content -->
    <main>
        <section id="">
            <div class="container-fluid">
                <!-- Resumo de Contratos -->
                <h5>RESUMO/CONTRATOS</h5>
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
                            <div class="card-header"><b><i class="bi bi-currency-dollar"></i> TOTAL GLOBAL</b></div>
                            <div class="card-body text-center">
                                <h2><b>R$ {{ number_format($valorTotal, 2, ',', '.') }}</b></h2>
                            </div>
                        </div>
                        <br>
                        <div class="card">
                            <div class="card-header">
                                <b><i class="bi bi-pie-chart-fill"></i> Por Categorias</b>
                            </div>

                            <div class="card-body">
                                <canvas id="categoriasChart"
                                    data-consumo="{{ $totais['valorConsumo'] }}"
                                    data-permanente="{{ $totais['valorPermanente'] }}"
                                    data-servico="{{ $totais['valorServico'] }}"
                                    style="height: 400px;">
                                </canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfico de Categoria -->
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header"><b><i class="bi bi-pie-chart-fill"></i> Por Ano</b></div>
                                <div class="card-body">
                                    <canvas id="contratosPorAnoExemplo"
                                            data-anos="{{ json_encode(array_column($contratosPorAno->toArray(), 'ano')) }}"
                                            data-totais="{{ json_encode(array_column($contratosPorAno->toArray(), 'total')) }}">
                                    </canvas>
                                </div>
                            </div>
                        </div>
                    </div>           
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><b><i class="bi bi-pie-chart-fill"></i> Por Mês</b></div>
                            <div class="card-body">
                            <canvas id="evolucaoCategoriasChart"
                                    data-meses="{{ json_encode(array_keys($dadosCategoriasPorMes)) }}"
                                    data-consumo="{{ json_encode(array_column($dadosCategoriasPorMes, 'consumo')) }}"
                                    data-permanente="{{ json_encode(array_column($dadosCategoriasPorMes, 'permanente')) }}"
                                    data-servico="{{ json_encode(array_column($dadosCategoriasPorMes, 'servico')) }}">
                            </canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    
@endsection
