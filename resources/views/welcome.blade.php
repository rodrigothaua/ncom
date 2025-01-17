<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar">
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
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                

                <!-- Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-header">Total de Processos</div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $totalProcessos }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-header">Total de Consumo</div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $totalConsumo }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-header">Total de Permanentes</div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $totalPermanente }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger mb-3">
                            <div class="card-header">Total de Serviços</div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $totalServico }}</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="row">
                    <div class="col-md-3">
                        <canvas id="processosChart" width="400" height="150"></canvas>
                    </div>
                </div>

                <!-- Table -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Lista de Processos</div>
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
        // Data for the chart
        const processosData = @json($processosChartData);

        const ctx = document.getElementById('processosChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Consumo', 'Permanente', 'Serviço'],
                datasets: [{
                    data: processosData,
                    backgroundColor: ['#007bff', '#ffc107', '#28a745']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
