@extends('layouts.app')

@section('title', 'NCON - Visão Geral')

@section('navbar')
    @include('layouts.navbar')
@endsection

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
                                                <h5 class="card-title">Total de Contratos: {{ isset($totalContratos) ? $totalContratos : 0 }}</h5>
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
                                                <h5 class="card-title"></h5>
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
                                                <h5 class="card-title"></h5>
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
                                                <h5 class="card-title"></h5>
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
                                                <h5 class="card-title"></h5>
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
                                                <h5 class="card-title"></h5>
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
                                        <h4><strong>R$ {{ number_format($valorTotal, 2, ',', '.') }}</strong></h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Gráfico de Categoria -->
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-header"><b><i class="bi bi-pie-chart-fill"></i> CATEGORIA CONTRATOS</b></div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <!-- Chart -->
                                                <canvas id="processosChart" width="600" height="400"></canvas>
                                            </div>
                                            <div class="col-6 total-categoria">
                                                <div class="card text-bg-success mb-3">
                                                    <div class="card-body">
                                                        <h5 class="card-title">R$ </h5>
                                                    </div>
                                                </div>

                                                <div class="card text-bg-warning mb-3">
                                                    <div class="card-body">
                                                        <h5 class="card-title">R$ </h5>
                                                    </div>
                                                </div>

                                                <div class="card text-bg-danger mb-3">
                                                    <div class="card-body">
                                                        <h5 class="card-title">R$ </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>                
                </div>
            </div>

            <!-- Tabela de Processos -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Número do Processo</th>
                        <th>Descrição</th>
                        <th>Requisitante</th>
                        <th>Data de Entrada</th>
                        <th>Valor Total</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($processos as $processo)
                        <tr>
                            <td>{{ $processo->numero_processo }}</td>
                            <td>{{ $processo->descricao }}</td>
                            <td>{{ $processo->requisitante }}</td>
                            <td>{{ $processo->data_entrada ? date('d/m/Y', strtotime($processo->data_entrada)) : '-' }}</td>
                            <td class="valor-total" data-consumo="{{ $processo->categorias ? $processo->categorias->valor_consumo : 0 }}" data-permanente="{{ $processo->categorias ? $processo->categorias->valor_permanente : 0 }}" data-servico="{{ $processo->categorias ? $processo->categorias->valor_servico : 0 }}"></td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick="mostrarDetalhes({{ json_encode($processo) }})">Detalhes</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </main>

    
@endsection
