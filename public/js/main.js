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
document.addEventListener("DOMContentLoaded", function() {
    function formatCurrency(value) {
        let num = parseFloat(value.replace(/\D/g, '')) / 100;
        return num.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    function parseCurrency(value) {
        if (!value) return 0;
        return parseFloat(value.replace(/[^\d,]/g, '').replace(',', '.')) || 0;
    }

    function calculateTotal() {
        let consumo = parseCurrency(document.querySelector('[name="valor_consumo"]').value);
        let permanente = parseCurrency(document.querySelector('[name="valor_permanente"]').value);
        let servico = parseCurrency(document.querySelector('[name="valor_servico"]').value);

        let total = consumo + permanente + servico;
        document.querySelector('[name="valor_total"]').value = total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    document.querySelectorAll('[name="valor_consumo"], [name="valor_permanente"], [name="valor_servico"]').forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');  // Remove all non-numeric characters
            if (value) {
                e.target.value = formatCurrency(value);
            } else {
                e.target.value = '';
            }
            calculateTotal();
        });
    });

    // Limpeza antes do envio
    document.querySelector("form").addEventListener("submit", function(e) {
        let consumoInput = document.querySelector('[name="valor_consumo"]');
        let permanenteInput = document.querySelector('[name="valor_permanente"]');
        let servicoInput = document.querySelector('[name="valor_servico"]');

        // Remover "R$" e vírgula
        consumoInput.value = consumoInput.value.replace(/[^\d,]/g, '').replace(',', '.');
        permanenteInput.value = permanenteInput.value.replace(/[^\d,]/g, '').replace(',', '.');
        servicoInput.value = servicoInput.value.replace(/[^\d,]/g, '').replace(',', '.');

        // Converter valor_total para formato correto (não vai precisar "R$")
        let totalValue = consumoInput.value.replace(/[^\d,]/g, '').replace(',', '.');
        document.querySelector('[name="valor_total"]').value = totalValue;
    });
});







