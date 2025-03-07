// CHART BAR POR CATEGORIAS
const ctxCategorias = document.getElementById('categoriasChart').getContext('2d');
const canvasCategorias = document.getElementById('categoriasChart');

const valorConsumo = canvasCategorias.dataset.consumo;
const valorPermanente = canvasCategorias.dataset.permanente;
const valorServico = canvasCategorias.dataset.servico;

const categoriasChart = new Chart(ctxCategorias, {
    type: 'pie',
    data: {
        labels: ['Consumo', 'Permanente', 'Serviço'],
        datasets: [{
            label: 'Categorias',
            data: [
                valorConsumo,
                valorPermanente,
                valorServico
            ],
            backgroundColor: [
                'rgba(0, 128, 0, 0.7)', // Verde
                'rgba(255, 255, 0, 0.7)', // Amarelo
                'rgba(0, 0, 255, 0.7)'  // Azul
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            datalabels: {
                formatter: (value, ctx) => {
                    return 'R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },
                color: '#fff',
                font: {
                    weight: 'bold'
                }
            }
        }
    },
});

// CHART HORIZONTAL POR ANO
document.addEventListener('DOMContentLoaded', function() {
    // ... outros gráficos ...

    // CHART: CONTRATOS POR ANO (EXEMPLO)
    const canvasContratosAno = document.getElementById('contratosPorAnoExemplo');
    const anos = JSON.parse(canvasContratosAno.dataset.anos);
    const totais = JSON.parse(canvasContratosAno.dataset.totais);

    const ctxContratosPorAnoExemplo = canvasContratosAno.getContext('2d');
    const contratosPorAnoExemplo = new Chart(ctxContratosPorAnoExemplo, {
        type: 'bar',
        data: {
            labels: anos,
            datasets: [{
                label: 'Total de Contratos',
                data: totais,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // ... outros gráficos ...

    // CHART: EVOLUÇÃO DAS CATEGORIAS (MULTI AXIS LINE CHART - MENSAL)
    const canvasEvolucaoCategorias = document.getElementById('evolucaoCategoriasChart');
    const meses = JSON.parse(canvasEvolucaoCategorias.dataset.meses);
    const consumo = JSON.parse(canvasEvolucaoCategorias.dataset.consumo);
    const permanente = JSON.parse(canvasEvolucaoCategorias.dataset.permanente);
    const servico = JSON.parse(canvasEvolucaoCategorias.dataset.servico);

    const ctxEvolucaoCategorias = canvasEvolucaoCategorias.getContext('2d');
    const evolucaoCategoriasChart = new Chart(ctxEvolucaoCategorias, {
        type: 'line',
        data: {
            labels: meses,
            datasets: [{
                label: 'Consumo',
                data: consumo,
                borderColor: 'rgba(54, 162, 235, 1)', // Azul
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                yAxisID: 'y'
            }, {
                label: 'Permanente',
                data: permanente,
                borderColor: 'rgba(255, 99, 132, 1)', // Vermelho
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                yAxisID: 'y1'
            }, {
                label: 'Serviço',
                data: servico,
                borderColor: 'rgba(75, 192, 192, 1)', // Verde
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                yAxisID: 'y2'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left'
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    }
                },
                y2: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
});