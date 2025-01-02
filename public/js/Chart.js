document.addEventListener("DOMContentLoaded", function () {
    fetch('/processos-pie-chart-data')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('processosPieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Verde', 'Amarelo', 'Vermelho'],
                    datasets: [{
                        data: [data.verde, data.amarelo, data.vermelho],
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                        hoverBackgroundColor: ['#218838', '#e0a800', '#c82333'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                    }
                }
            });
        })
        .catch(error => console.error('Erro ao carregar os dados do gráfico:', error));
});