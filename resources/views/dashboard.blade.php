<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Ncom</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar bg-dark text-light p-3" style="height: 100vh; position: fixed; width: 250px;">
        <h2>Ncom</h2>
        <a href="/dashboard" class="d-block text-light py-2">Dashboard</a>
        <a href="/users" class="d-block text-light py-2">Usuários</a>
        <a href="{{ route('processos.index') }}" class="d-block text-light py-2">Processos de Compras</a>
        <a href="/settings" class="d-block text-light py-2">Configurações</a>
        <a href="/logout" class="d-block text-light py-2">Sair</a>
    </div>

    <!-- Main Content -->
    <div class="content" style="margin-left: 250px; padding: 20px;">
        <h1>Bem-vindo, {{ Auth::user()->name }}!</h1>
        <p class="text-muted">Resumo do sistema:</p>

        <!-- Estatísticas -->
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total de Usuários</h5>
                        <p class="card-text fs-4">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Novos Usuários (7 dias)</h5>
                        <p class="card-text fs-4">{{ $recentUsers }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Cadastros nos Últimos 7 Dias</h5>
                <canvas id="userChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Dados do gráfico enviados pelo Laravel
        const labels = @json($labels);
        const data = @json($data);

        // Configuração do Chart.js
        const ctx = document.getElementById('userChart').getContext('2d');
        const userChart = new Chart(ctx, {
            type: 'line', // Tipo do gráfico
            data: {
                labels: labels, // Datas
                datasets: [{
                    label: 'Usuários Cadastrados',
                    data: data, // Contagem de cadastros
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false // Ocultar legenda
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Data'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Cadastros'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
