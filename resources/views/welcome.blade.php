@extends('layouts.app')

@section('title', 'NCON - Visão Geral')

@section('content')
    <div class="container-fluid">
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
                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/login">Entrar</a>
                            </li>
                            <!--
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                </ul>
                            </li>
                        </ul>
                       
                        <form class="d-flex mt-3" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>-->
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- Main Content -->
    <main class="container-fluid">
        <div id="filtro" class="col-lg-12" style="display: none;">
            <div class="card">
                <div class="card-header">
                    <b><i class="bi bi-search"></i> FILTRO</b>
                </div>
                <div class="card-body">
                    <fieldset class="form-group">
                        <form method="GET" action="{{ route('home') }}">
                            <div class="row">
                                <!-- Número do Processo -->
                                <div class="col-md-6">
                                    <label for="numero_processo" class="control-label">Número do Processo</label>
                                    <select class="form-select select2" id="numero_processo" name="numero_processo">
                                        <option value="" selected>Selecione...</option>
                                        @foreach ($processos as $processo)
                                            <option value="{{ $processo->id }}" {{ old('numero_processo', request('numero_processo')) == $processo->id ? 'selected' : '' }}>
                                                {{ $processo->numero_processo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Valor -->
                                <div class="col-md-6">
                                    <label for="valor" class="control-label">Valor</label>
                                    <input type="number" class="form-control" id="valor" name="valor" value="{{ old('valor', request('valor')) }}">
                                </div>

                                <!-- Requisitante -->
                                <div class="col-md-6">
                                    <label for="requisitante" class="control-label">Requisitante</label>
                                    <select class="form-select select2" id="requisitante" name="requisitante">
                                        <option value="" selected>Selecione...</option>
                                        @foreach ($requisitantes as $requisitante)
                                            <option value="{{ $requisitante->requisitante }}" {{ old('requisitante'), request('requisitante') == $requisitante->requisitante ? 'selected' : '' }}>
                                                {{ $requisitante->requisitante }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Data Início -->
                                <div class="col-md-6">
                                    <label for="data_inicio" class="control-label">Data Início</label>
                                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}">
                                </div>
                            </div>

                            <br>
                            <button class="btn btn-success text-right" type="submit">
                                <i class="fa fa-search"></i> Filtrar
                            </button>
                        </form>
                    </fieldset>
                </div>
            </div>
        </div>
            
        <br>

        <!-- Cards -->
        <div class="card">
            <div class="card-header">
                <b>RESUMO/CONTRATOS</b>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <!-- Total de Contratos -->
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
                                    <div class="card-header">VENCEM(60 A 90 DIAS)</div>
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
                                <h4><strong>R${{ number_format($valorTotal, 2, ',', '.') }}</strong></h4>
                            </div>
                        </div>
                        <br>
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
                                                <h5 class="card-title">R$ {{ number_format($valorConsumo, 2, ',', '.') }}</h5>
                                                <!--<p class="card-text">{{ $totalConsumo }}</p>-->
                                            </div>
                                        </div>

                                        <div class="card text-bg-warning mb-3">
                                            <div class="card-body">
                                                <h5 class="card-title">R$ {{ number_format($valorPermanente, 2, ',', '.') }}</h5>
                                            </div>
                                        </div>

                                        <div class="card text-bg-danger mb-3">
                                            <div class="card-body">
                                                <h5 class="card-title">R$ {{ number_format($valorServico, 2, ',', '.') }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header"><b><i class="bi bi-graph-up"></i> POR ANO</b></div>
                            <div class="card-body text-center">
                                <canvas id="barChart"></canvas>

                                <!-- Div escondida para passar os dados para o JavaScript -->
                                <div id="barChartData" 
                                    data-labels="{{ json_encode($labels) }}" 
                                    data-data="{{ json_encode($data) }}" 
                                    style="display: none;"></div>
                                </div>
                            </div>
                        </div>                          
                    </div>
                </div> 
            </div>                
        </div>

        <!-- Cronograma Mensal -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><b><i class="bi bi-graph-up"></i> CRONOGRAMA MENSAL</b></div>
                    <div class="card-body">
                        <canvas id="barVerticalChart" style="max-width: 100%; height: 200px;"></canvas>

                        <!-- Dados específicos para o gráfico -->
                        <div id="barVerticalChartData" 
                            data-labels="{{ json_encode($labelsBarVertical) }}" 
                            data-data="{{ json_encode($dataBarVertical) }}" 
                            data-media="{{ $mediaEixoYBarVertical }}" 
                            style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Processos -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><b><i class="bi bi-list-task"></i> LISTA DE PROCESSOS</b></div>
                    <div class="card-body">
                        <input class="form-control" id="myInput" type="text" placeholder="Pesquisar..">
                        @if(isset($processosFiltrados) && count($processosFiltrados) > 0)
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Número</th>
                                        <th>Descrição</th>
                                        <th>Requisitante</th>
                                        <th>Categoria</th>
                                        <th>Valor Total</th>
                                        <th>Data de Início</th>
                                        <th>Data de Vencimento</th>
                                    </tr>
                                </thead>
                                <tbody id="myTable">
                                    @foreach ($processosFiltrados as $processo)
                                        <tr>
                                            <td>{{ $processo->id }}</td>
                                            <td>{{ $processo->numero_processo }}</td>
                                            <td>{{ $processo->descricao }}</td>
                                            <td>{{ $processo->requisitante }}</td>
                                            <td>
                                                @php
                                                    $categorias = is_string($processo->categoria) ? json_decode($processo->categoria, true) : $processo->categoria;
                                                @endphp

                                                @if (!empty($categorias) && is_array($categorias))
                                                    {{ implode(', ', $categorias) }}
                                                @else
                                                    Nenhuma
                                                @endif
                                            </td>
                                            <td>R$ {{ number_format($processo->valor_total, 2, ',', '.') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($processo->data_inicio)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($processo->data_vencimento)->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">Nenhum processo encontrado com os filtros aplicados.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>    
    </main>

    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('processosChart').getContext('2d');
            const processosChartData = @json($processosChartData);

            new Chart(ctx, {
                type: 'pie', // Tipo de gráfico (torta)
                data: processosChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Permite controlar a largura e altura manualmente
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Distribuição de Processos por Categoria'
                        }
                    }
                },
            });
        });
    </script>


</body>
</html>
