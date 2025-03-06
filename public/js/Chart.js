// Chart POR CATEGORIAS
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
