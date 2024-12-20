document.addEventListener('DOMContentLoaded', function () {
    fetch('/processos-pie-chart-data')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('processosPieChart').getContext('2d');
            const processosChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Vencidos', 'Perto de Vencer', 'Moderado', 'Sem Cor'],
                    datasets: [{
                        data: [data.vermelho, data.amarelo, data.laranja, data.sem_cor],
                        backgroundColor: ['#dc3545', '#ffc107', '#fd7e14', '#d3d3d3'], // Cores
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.label || '';
                                    if (context.raw) {
                                        label += ': ' + context.raw;
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Erro ao carregar dados do gráfico:', error);
        });
});