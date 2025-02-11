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
    // Seleciona os campos de entrada de valores
    let consumoInput = document.getElementById('valor_consumo');
    let permanenteInput = document.getElementById('valor_permanente');
    let servicoInput = document.getElementById('valor_servico');
    let valorTotalInput = document.getElementById('valor_total');

    // Função que calcula e atualiza o valor total
    function calcularTotal() {
        let valorConsumo = parseFloat(consumoInput.value) || 0;
        let valorPermanente = parseFloat(permanenteInput.value) || 0;
        let valorServico = parseFloat(servicoInput.value) || 0;

        // Soma os valores
        let valorTotal = valorConsumo + valorPermanente + valorServico;

        // Atualiza o campo de valor total
        valorTotalInput.value = valorTotal.toFixed(2); // Arredonda para 2 casas decimais
    }

    // Adiciona os eventos para recalcular o valor total quando os campos forem alterados
    consumoInput.addEventListener('input', calcularTotal);
    permanenteInput.addEventListener('input', calcularTotal);
    servicoInput.addEventListener('input', calcularTotal);

    // Chama a função uma vez para garantir que o valor total esteja atualizado ao carregar a página
    calcularTotal();
});




