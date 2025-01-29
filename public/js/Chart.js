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


function renderBarVerticalChart() {
    // Obter os dados específicos deste gráfico
    const chartDataElement = document.getElementById('barVerticalChartData');
    if (!chartDataElement) return; // Se não existir, sai da função

    const labels = JSON.parse(chartDataElement.dataset.labels); // Labels (meses)
    const data = JSON.parse(chartDataElement.dataset.data);    // Dados (valores)
    const mediaEixoY = JSON.parse(chartDataElement.dataset.media); // Média do eixo Y

    // Criar o gráfico de barras verticais
    const ctx = document.getElementById('barVerticalChart').getContext('2d');
    const anoAtual = new Date().getFullYear(); // Obtém o ano atual dinamicamente
    document.getElementById('barVerticalChart').style.height = "400px";
    const barVerticalChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                `Jan/${anoAtual}`, `Fev/${anoAtual}`, `Mar/${anoAtual}`, `Abr/${anoAtual}`, 
                `Mai/${anoAtual}`, `Jun/${anoAtual}`, `Jul/${anoAtual}`, `Ago/${anoAtual}`, 
                `Set/${anoAtual}`, `Out/${anoAtual}`, `Nov/${anoAtual}`, `Dez/${anoAtual}`
            ],
            datasets: [{
                label: 'Valor Total dos Processos por Mês',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.6)', // Cor das barras
                borderColor: 'rgba(54, 162, 235, 1)', // Cor da borda
                borderWidth: 1,
                barThickness: 30, // Define a largura das barras (reduza esse valor para barras mais finas)
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Permite ajustar o tamanho sem deformação
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR'); // Converte para Real brasileiro
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 14 // Ajusta o tamanho das legendas do eixo X
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 14 // Ajusta o tamanho da legenda
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw;
                            return 'R$ ' + value.toLocaleString('pt-BR'); // Exibe valores formatados no tooltip
                        }
                    }
                }
            },
            categoryPercentage: 0.8,  // Diminui o espaçamento entre categorias (padrão é 0.8)
            barPercentage: 0.9        // Deixa as barras mais largas (padrão é 0.9)
        }
    });
}
document.addEventListener('DOMContentLoaded', function () {
    // Inicializar o gráfico de barras verticais
    renderBarVerticalChart();
});