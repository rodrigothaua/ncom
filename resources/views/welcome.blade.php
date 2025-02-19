@extends('layouts.app')

@section('title', 'NCON - Visão Geral')

@section('content')
    <div class="">
        <nav class="navbar navbar-dark bg-dark border-bottom border-body fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('img/logo.png') }}" width="30" height="30" class="d-inline-block align-top" alt="">
                    SIGECOM
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#" style="color: #000;">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/login" style="color: #000;">Entrar</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div><br>

    <!-- Main Content -->
    <main>
        <section id="home">
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
                                                <h5 class="card-title">Total de Contratos: </h5>
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
                                                <h5 class="card-title">{{ $totalMenos30Dias }}</h5>
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
                                                <h5 class="card-title">{{ $totalEntre30e60Dias }}</h5>
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
                                                <h5 class="card-title">{{ $totalEntre60e90Dias }}</h5>
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
                                                <h5 class="card-title">{{ $totalEntre90e180Dias }}</h5>
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
                                                <h5 class="card-title">{{ $totalMais180Dias }}</h5>
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
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Lista de Processos</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Processo</th>
                                            <th>Requisitante</th>
                                            <th>Data de Início</th>
                                            <th>Data de Vencimento</th>
                                            <th>Status</th>
                                            <th>Detalhes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Loop para exibir os processos -->
                                        @foreach($processos as $processo)
                                            <tr>
                                                <td>{{ $processo->id }}</td>
                                                <td>{{ $processo->nome }}</td> <!-- Nome do processo -->
                                                <td>{{ $processo->requisitante }}</td> <!-- Requisitante -->
                                                <td>{{ \Carbon\Carbon::parse($processo->data_inicio)->format('d/m/Y') }}</td> <!-- Data de Início -->
                                                <td>{{ \Carbon\Carbon::parse($processo->data_vencimento)->format('d/m/Y') }}</td> <!-- Data de Vencimento -->
                                                <td>
                                                    @if(\Carbon\Carbon::parse($processo->data_vencimento)->isPast())
                                                        <span class="badge bg-danger">Vencido</span>
                                                    @elseif(\Carbon\Carbon::parse($processo->data_vencimento)->diffInDays() <= 30)
                                                        <span class="badge bg-warning">Pendente</span>
                                                    @else
                                                        <span class="badge bg-success">Ativo</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('processos.show', $processo->id) }}" class="btn btn-info btn-sm">Ver Detalhes</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <!-- Fim do Loop -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    
@endsection
