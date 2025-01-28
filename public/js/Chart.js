// Obter os dados enviados pelo backend
const labels = JSON.parse(document.getElementById('barChartData').dataset.labels); // Anos
const data = JSON.parse(document.getElementById('barChartData').dataset.data);    // Totais de processos

// Configurar o gráfico
const ctx = document.getElementById('barChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels, // Anos no eixo Y
        datasets: [{
            label: 'Total de Processos',
            data: data, // Totais de processos
            backgroundColor: 'rgba(54, 162, 235, 0.5)', // Cor das barras
            borderColor: 'rgba(54, 162, 235, 1)',       // Cor da borda
            borderWidth: 1
        }]
    },
    options: {
        indexAxis: 'y', // Exibir como gráfico horizontal
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            x: {
                beginAtZero: true // Começa do zero no eixo X
            }
        }
    }
});
