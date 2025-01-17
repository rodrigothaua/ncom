fetch('/processos-pie-chart-data')
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('processosPieChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Verde', 'Amarelo', 'Vermelho'],
                datasets: [{
                    label: 'Processos',
                    data: [data.verde, data.amarelo, data.vermelho],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true
            }
        });
    })
    .catch(error => console.error('Erro ao carregar os dados do gráfico:', error));
;

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
    