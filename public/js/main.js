document.addEventListener('DOMContentLoaded', function () {
    // Obtenha os dados do backend
    fetch('/processos-pie-chart-data')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('processosPieChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: ['#ff4d4d', '#ffd633', '#ffa31a', '#33cc33'], // Vermelho, Amarelo, Laranja, Verde
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Distribuição de Processos por Status de Data'
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Erro ao carregar os dados do gráfico:', error);
        });
});

// SCRIPT DE PESQUISA
$(document).ready(function(){
    $("#myInput").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#myTable tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
});

//ABRIR DATE AO CLICAR NO INPUT
document.addEventListener("DOMContentLoaded", function () {
    const inputsData = document.querySelectorAll('input[type="date"]');

    inputsData.forEach(input => {
        input.addEventListener("click", function () {
            this.showPicker(); // Abre o seletor de data automaticamente
        });
    });
});

//ABRIR VALORES E DATAS /processos/create.blade
document.getElementById('indeterminateCheckbox').addEventListener('change', function() {
    const optionalFields = document.getElementById('optionalFields');
    optionalFields.style.display = this.checked ? 'block' : 'none';

    // Limpar valores se não estiver selecionado
    if (!this.checked) {
        document.getElementById('valor_total').value = '';
        document.getElementById('data_inicio').value = '';
        document.getElementById('data_vencimento').value = '';
    }
});

//script para somar os valores das categorias no valor total
// create.blade
document.addEventListener("DOMContentLoaded", function () {
    // Seleciona os campos de valor e o campo de valor total
    const valorConsumo = document.getElementById("valor_consumo");
    const valorPermanente = document.getElementById("valor_permanente");
    const valorServico = document.getElementById("valor_servico");
    const valorTotal = document.getElementById("valor_total");

    // Função para calcular e atualizar o valor total
    function calcularValorTotal() {
        // Converte os valores para números, considerando que podem estar formatados como moeda
        const consumo = parseFloat(valorConsumo.value.replace('R$', '').replace(',', '.')) || 0;
        const permanente = parseFloat(valorPermanente.value.replace('R$', '').replace(',', '.')) || 0;
        const servico = parseFloat(valorServico.value.replace('R$', '').replace(',', '.')) || 0;

        // Soma os valores
        const total = consumo + permanente + servico;

        // Atualiza o campo de valor total com o valor calculado
        valorTotal.value = total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    // Adiciona ouvintes de evento para recalcular o valor total quando o valor for alterado
    valorConsumo.addEventListener("input", calcularValorTotal);
    valorPermanente.addEventListener("input", calcularValorTotal);
    valorServico.addEventListener("input", calcularValorTotal);
});




