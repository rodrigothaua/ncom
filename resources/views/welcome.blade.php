@extends('layouts.app')

@section('title', 'NCON - Visão Geral')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar
            <nav class="navbar bg-dark border-bottom border-body" data-bs-theme="dark"">
                <div class="position-sticky">
                    <ul class="nav flex-column text-white p-3">
                        <li class="nav-item">
                            <h4 class="text-white">Home</h4>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#">Consulta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="{{ route('processos.index') }}">Entrar</a>
                        </li>
                    </ul>
                </div>
            </nav>-->

            <!-- Main Content -->
            <main class="container-fluid ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Visão Geral</h1>
                    <ul class="nav justify-content-end">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/login">Entrar</a>
                        </li>
                    </ul>
                </div>

                <div id="filtro" class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <b>FILTRO</b>
                        </div>
                        <div class="card-body">
                            <fieldset class="form-group">
                                <form method="GET" action="#">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="orgao" class="control-label">
                                                    Órgão
                                                </label>
                                                <select class="form-select select2" id="orgao" name="orgao"></select>    
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="unidade" class="control-label">
                                                    Unidade Gestora
                                                </label>
                                                <select class="form-select select2" id="unidade" name="unidade"></select>    
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group"  >
                                                <label for="fornecedor" class="control-label">
                                                    Fornecedor
                                                </label>
                                                <select class="form-select select2" id="fornecedor" name="fornecedor"></select>    
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group"  >
                                                <label for="contrato" class="control-label">
                                                    Contrato
                                                </label>
                                                <select class="form-select select2" id="contrato" name="contrato"></select>    
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <button class="btn btn-success text-right" type="submit"><i class="fa fa-search"></i> Filtrar</button>         
                                </form>
                            </fieldset>
                        </div>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <b>Valor total dos Processos</b>
                            </div>
                            <div class="card-body">
                                <h4><strong>{{ number_format($valorTotal, 2, ',', '.') }}</strong></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <b>Valor por categoria</b>
                            </div>
                            <div class="card-body">
                                
                            </div>
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
                            <div class="col-4">
                                <div class="col">
                                    <div class="card text-white bg-primary mb-3">
                                        <div class="card-header">Total de Contratos</div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $totalProcessos }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card text-white bg-success mb-3">
                                        <div class="card-header">Total de Consumo</div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $totalConsumo }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card text-white bg-warning mb-3">
                                        <div class="card-header">Total de Permanentes</div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $totalPermanente }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card text-white bg-danger mb-3">
                                        <div class="card-header">Total de Serviços</div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $totalServico }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- Chart -->
                                <canvas id="processosChart" width="400" height="200"></canvas>
                            </div>
                            <div class="col-md-4">
                                <div class="col">
                                    <div class="card text-white bg-danger mb-3">
                                        <div class="card-header">VENCEM(-30 DIAS)</div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $totalMenos30Dias }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card text-white mb-3" style="background: #FF7701;">
                                        <div class="card-header">VENCEM (30 A 60 DIAS)</div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $totalEntre30e60Dias }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card text-white bg-warning mb-3">
                                        <div class="card-header">VENCEM(60 A 90 DIAS)</div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $totalEntre60e90Dias }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card text-white bg-success mb-3">
                                        <div class="card-header">VENCEM(90 A 180 DIAS)</div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $totalEntre90e180Dias }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="card text-white bg-primary mb-3">
                                        <div class="card-header">VENCEM(+ 180 DIAS)</div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $totalMais180Dias }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                    
                </div>

                

                <!-- Table -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><b>LISTA DE PROCESSOS</b></div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Número</th>
                                            <th>Descrição</th>
                                            <th>Categoria</th>
                                            <th>Valor Total</th>
                                            <th>Data de Início</th>
                                            <th>Data de Vencimento</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($processos as $processo)
                                            <tr>
                                                <td>{{ $processo->id }}</td>
                                                <td>{{ $processo->numero_processo }}</td>
                                                <td>{{ $processo->descricao }}</td>
                                                <td>{{ ucfirst($processo->categoria) }}</td>
                                                <td>R$ {{ number_format($processo->valor_total, 2, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($processo->data_inicio)->format('d/m/Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($processo->data_vencimento)->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('processosChart').getContext('2d');
            const processosChartData = @json($processosChartData);

            new Chart(ctx, {
                type: 'pie', // Tipo de gráfico (torta)
                data: processosChartData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
