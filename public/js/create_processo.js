function formatarNumeroProcesso(input) {
    let value = input.value.replace(/\D/g, ''); // Remove tudo que não for número

    if (value.length > 4) value = value.replace(/^(\d{4})/, '$1.');
    if (value.length > 10) value = value.replace(/^(\d{4})\.(\d{6})/, '$1.$2/');
    if (value.length > 14) value = value.replace(/^(\d{4})\.(\d{6})\/(\d{4})/, '$1.$2/$3-');
    if (value.length > 16) value = value.slice(0, 19); // Limita o comprimento a 16 caracteres

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
//// ADICIONAR PA /////
function adicionarDespesa(tipo) {
    const container = document.getElementById('pa_' + tipo + '_container');
    const index = container.children.length;
    const div = document.createElement('div');
    div.innerHTML = `
        <div class="row mt-1">
            <div class="col-md-12">
                <label>Número PA</label>
                <select name="${tipo}_despesas[${index}][numero_pa]" class="form-control">
                    <option value="">Selecione um PA</option>
                    <option value="1.1.11.11">1.1.11.11</option>
                    <option value="2.2.22.22">2.2.22.22</option>
                    <option value="3.3.33.33">3.3.33.33</option>
                </select>
            </div>
            <div class="col-md-12">
                <label>Natureza Despesa</label>
                <input type="text" name="${tipo}_despesas[${index}][natureza_despesa]" class="form-control">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-danger mt-4" onclick="removerDespesa(this)">Remover</button>
            </div>
        </div>
    `;
    container.appendChild(div);
}

function removerDespesa(button) {
    button.parentElement.parentElement.remove();
}

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
                        <input type="text" class="form-control" name="contratos[${contratoIndex}][numero_contrato]">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Valor do Contrato</label>
                        <input type="text" class="form-control money-input" name="contratos[${contratoIndex}][valor_contrato]">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data Inicial</label>
                        <input type="date" class="form-control" name="contratos[${contratoIndex}][data_inicial_contrato]">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Data Final</label>
                        <input type="date" class="form-control" name="contratos[${contratoIndex}][data_final_contrato]">
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
    document.addEventListener("DOMContentLoaded", function() {
        let camposValor = [
            { input: "valor_consumo", container: "pa_consumo_container" },
            { input: "valor_permanente", container: "pa_permanente_container" },
            { input: "valor_servico", container: "pa_servico_container" }
        ];

        camposValor.forEach(campo => {
            let input = document.querySelector(`input[name='${campo.input}']`);
            let container = document.getElementById(campo.container);

            if (input) {
                input.addEventListener("input", function() {
                    if (this.value.trim() !== "") {
                        container.classList.remove("d-none");
                    } else {
                        container.classList.add("d-none");
                    }
                });
            }
        });
    });