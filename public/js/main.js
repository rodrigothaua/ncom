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

function mostrarDetalhes(processo) {
        document.getElementById("det-numero").innerText = processo.numero_processo;
        document.getElementById("det-descricao").innerText = processo.descricao;
        document.getElementById("det-requisitante").innerText = processo.requisitante;
        document.getElementById("det-data-entrada").innerText = processo.data_entrada ? new Date(processo.data_entrada).toLocaleDateString("pt-BR") : '-';
        document.getElementById("det-modalidade").innerText = processo.modalidade;
        document.getElementById("det-procedimentos").innerText = processo.procedimentos_auxiliares;

        // Categorias
        if (processo.categorias) {
            document.getElementById("det-valor-consumo").innerText = Number(processo.categorias.valor_consumo).toLocaleString("pt-BR", { minimumFractionDigits: 2 });
            document.getElementById("det-valor-permanente").innerText = Number(processo.categorias.valor_permanente).toLocaleString("pt-BR", { minimumFractionDigits: 2 });
            document.getElementById("det-valor-servico").innerText = Number(processo.categorias.valor_servico).toLocaleString("pt-BR", { minimumFractionDigits: 2 });
            document.getElementById("det-valor-total").innerText = Number(processo.valor_total).toLocaleString("pt-BR", { minimumFractionDigits: 2 });
        }

        // Detalhes das Despesas
        if (processo.categorias && processo.categorias.detalhes_despesa) {
            document.getElementById("det-pa-consumo").innerText = processo.categorias.detalhes_despesa.pa_consumo || '-';
            document.getElementById("det-nd-consumo").innerText = processo.categorias.detalhes_despesa.nd_consumo || '-';
            document.getElementById("det-pa-permanente").innerText = processo.categorias.detalhes_despesa.pa_permanente || '-';
            document.getElementById("det-nd-permanente").innerText = processo.categorias.detalhes_despesa.nd_permanente || '-';
            document.getElementById("det-pa-servico").innerText = processo.categorias.detalhes_despesa.pa_servico || '-';
            document.getElementById("det-nd-servico").innerText = processo.categorias.detalhes_despesa.nd_servico || '-';
        }

        // Contratos
        let contratosLista = document.getElementById("det-contratos");
        contratosLista.innerHTML = "";
        if (processo.contratos.length > 0) {
            processo.contratos.forEach(contrato => {
                let li = document.createElement("li");
                li.className = "list-group-item";
                li.innerHTML = `<strong>Nº:</strong> ${contrato.numero_contrato} | <strong>Valor:</strong> R$ ${Number(contrato.valor_contrato).toLocaleString("pt-BR", { minimumFractionDigits: 2 })} | <strong>Data Inicial:</strong> ${new Date(contrato.data_inicial_contrato).toLocaleDateString("pt-BR")} | <strong>Data Final:</strong> ${new Date(contrato.data_final_contrato).toLocaleDateString("pt-BR")}`;
                contratosLista.appendChild(li);
            });
        } else {
            contratosLista.innerHTML = "<li class='list-group-item text-muted'>Nenhum contrato</li>";
        }

        var modal = new bootstrap.Modal(document.getElementById('modalDetalhes'));
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        atualizarValoresTotais();
    });

    function atualizarValoresTotais() {
        const valoresTotais = document.querySelectorAll('.valor-total');
        valoresTotais.forEach(valorTotal => {
            const consumo = parseFloat(valorTotal.dataset.consumo) || 0;
            const permanente = parseFloat(valorTotal.dataset.permanente) || 0;
            const servico = parseFloat(valorTotal.dataset.servico) || 0;
            const total = consumo + permanente + servico;
            valorTotal.textContent = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(total);
        });
    }







