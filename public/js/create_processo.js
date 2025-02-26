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