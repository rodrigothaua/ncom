function formatarNumeroProcesso(input) {
    let value = input.value.replace(/\D/g, ''); // Remove tudo que não for número

    if (value.length > 5) value = value.replace(/^(\d{5})/, '$1.');
    if (value.length > 11) value = value.replace(/^(\d{5})\.(\d{6})/, '$1.$2/');
    if (value.length > 15) value = value.replace(/^(\d{5})\.(\d{6})\/(\d{4})/, '$1.$2/$3-');

    input.value = value;
}


document.addEventListener("DOMContentLoaded", function () {
    const checkbox = document.getElementById("indeterminateCheckbox");
    const optionalFields = document.getElementById("optionalFields");

    checkbox.addEventListener("change", function () {
        if (checkbox.checked) {
            optionalFields.style.display = "block";
            tornarCamposObrigatorios(true);
        } else {
            optionalFields.style.display = "none";
            tornarCamposObrigatorios(false);
        }
    });

    function tornarCamposObrigatorios(ativo) {
        document.querySelectorAll("#optionalFields select, #contratos input, #contratos textarea").forEach((campo) => {
            if (ativo) {
                campo.setAttribute("required", "required");
            } else {
                campo.removeAttribute("required");
            }
        });
    }
});
////////////////////////
// ADICIONAR CONTRATO //
    let contratoIndex = 0;

    function adicionarContrato() {
        contratoIndex++;
        const contratoHTML = `
            <div class="card mt-3 p-3 border shadow-sm" id="contrato_${contratoIndex}">
                <div class="row g-3">
                    <div class="col-12">
                        <h5>Contrato ${contratoIndex}</h5>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Número do Contrato</label>
                        <input type="text" class="form-control" name="contratos[${contratoIndex}][numero_contrato]" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Valor do Contrato</label>
                        <input type="text" class="form-control money-input" name="contratos[${contratoIndex}][valor_contrato]" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data Inicial</label>
                        <input type="date" class="form-control" name="contratos[${contratoIndex}][data_inicial_contrato]" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data Final</label>
                        <input type="date" class="form-control" name="contratos[${contratoIndex}][data_final_contrato]" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Observações</label>
                        <textarea class="form-control" name="contratos[${contratoIndex}][observacao]" rows="2"></textarea>
                    </div>
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removerContrato(${contratoIndex})">Excluir</button>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('contratos').insertAdjacentHTML('beforeend', contratoHTML);

        // Aplicar máscara nos inputs de valores monetários
        document.querySelectorAll(".money-input").forEach(function (input) {
            IMask(input, {
                mask: "R$ num",
                blocks: {
                    num: {
                        mask: Number,
                        thousandsSeparator: ".",
                        radix: ",",
                        mapToRadix: ["."],
                        scale: 2
                    }
                }
            });
        });

        // Tornar os inputs de data clicáveis e focáveis
        document.querySelectorAll("input[type='date']").forEach(input => {
            input.addEventListener("focus", function () {
                this.showPicker(); // Abre automaticamente o seletor de data
            });
        });
    }

    function removerContrato(index) {
        document.getElementById(`contrato_${index}`).remove();
    }

    ///////////////////////////
    /// ADICIONAR Nº PA //////
    document.addEventListener('DOMContentLoaded', function () {
    let categorias = ['consumo', 'permanente', 'servico'];

    categorias.forEach(tipo => {
        let inputValor = document.getElementById(`valor_${tipo}`);
        let paContainer = document.getElementById(`pa_${tipo}_container`);
        let selectContainer = document.getElementById(`select_${tipo}_container`);

        inputValor.addEventListener('input', function () {
            if (this.value.trim() !== '') {
                paContainer.classList.remove('d-none'); // Exibe o título e os inputs de PA
                if (!paContainer.querySelector('.pa-group')) {
                    criarPAContainer(paContainer, tipo);
                }
                selectContainer.classList.remove('d-none'); // Mostra o select
            } else {
                paContainer.classList.add('d-none');
                paContainer.innerHTML = ''; // Remove os inputs de PA
                selectContainer.classList.add('d-none'); // Oculta o select
            }
        });
    });
});

// Função para criar a área de Número de PA
function criarPAContainer(container, tipo) {
    let paGroup = document.createElement('div');
    paGroup.classList.add('pa-group');

    let inputPA = criarPAInput(tipo);
    paGroup.appendChild(inputPA);
    container.appendChild(paGroup);
}

// Função para criar um input de PA
function criarPAInput(tipo) {
    let container = document.getElementById(`pa_${tipo}_container`);

    let div = document.createElement('div');
    div.classList.add('input-group', 'mt-2');

    let input = document.createElement('input');
    input.type = 'text';
    input.className = 'form-control pa-input';
    input.name = `pa_${tipo}[]`;
    input.placeholder = "Número do PA";

    setTimeout(() => {
        IMask(input, { mask: '0.0.00.00' });
    }, 100);

    let btnAdicionar = document.createElement('button');
    btnAdicionar.type = 'button';
    btnAdicionar.className = 'btn btn-success btn-sm';
    btnAdicionar.textContent = '+';
    btnAdicionar.onclick = function () {
        container.appendChild(criarPAInput(tipo));
        atualizarBotoesPA(tipo);
    };

    let btnRemover = document.createElement('button');
    btnRemover.type = 'button';
    btnRemover.className = 'btn btn-danger btn-sm';
    btnRemover.textContent = '-';
    btnRemover.onclick = function () {
        div.remove();
        atualizarBotoesPA(tipo);
    };

    div.appendChild(input);
    div.appendChild(btnAdicionar);
    div.appendChild(btnRemover);

    atualizarBotoesPA(tipo);
    return div;
}

// Atualiza os botões para manter apenas "+" no último input
function atualizarBotoesPA(tipo) {
    let container = document.getElementById(`pa_${tipo}_container`);
    let paInputs = container.getElementsByClassName("input-group");

    Array.from(paInputs).forEach((inputGroup, index) => {
        let buttons = inputGroup.querySelectorAll("button");
        buttons.forEach(btn => btn.remove()); // Remove botões antigos

        let btnRemover = document.createElement('button');
        btnRemover.type = 'button';
        btnRemover.className = 'btn btn-danger btn-sm';
        btnRemover.textContent = '-';
        btnRemover.onclick = function () {
            inputGroup.remove();
            atualizarBotoesPA(tipo);
        };
        inputGroup.appendChild(btnRemover);

        if (index === paInputs.length - 1) {
            let btnAdicionar = document.createElement('button');
            btnAdicionar.type = 'button';
            btnAdicionar.className = 'btn btn-success btn-sm';
            btnAdicionar.textContent = '+';
            btnAdicionar.onclick = function () {
                container.appendChild(criarPAInput(tipo));
                atualizarBotoesPA(tipo);
            };
            inputGroup.appendChild(btnAdicionar);
        }
    });
}