// Não precisamos mais desta função pois o IMask cuidará da formatação
function formatarNumeroProcesso(input) {
    // A formatação será feita pelo IMask
}


    document.addEventListener("DOMContentLoaded", function () {
        // Função para verificar e mostrar containers que possuem valores antigos
        function verificarContainersAtivos() {
            ['consumo', 'permanente', 'servico'].forEach(tipo => {
                const input = document.querySelector(`input[name='valor_${tipo}']`);
                const container = document.getElementById(`pa_${tipo}_container`);
                const paInput = container?.querySelector(`input[name='${tipo}_despesa[numero_pa]']`);
                const ndInput = container?.querySelector(`input[name='${tipo}_despesa[natureza_despesa]']`);

                // Mostra container se houver valores em qualquer campo
                if ((input && input.value && input.value.trim() !== '') || 
                    (paInput && paInput.value && paInput.value.trim() !== '') ||
                    (ndInput && ndInput.value && ndInput.value.trim() !== '')) {
                    container?.classList.remove('d-none');
                }

                // Formata os valores existentes
                if (input && input.value) {
                    IMask(input, {
                        mask: "R$ num",
                        blocks: {
                            num: {
                                mask: Number,
                                thousandsSeparator: ".",
                                radix: ",",
                                mapToRadix: ["."],
                                scale: 2,
                                padFractional: true
                            }
                        }
                    });
                }
            });
        }

        // Verificar containers ativos na inicialização
        verificarContainersAtivos();

        // Verificar e mostrar containers PA se houver novos valores
        ['consumo', 'permanente', 'servico'].forEach(tipo => {
            const input = document.querySelector(`input[name='valor_${tipo}']`);
            const container = document.getElementById(`pa_${tipo}_container`);
            
        // Verifica se há valor e mostra o container
        if (input && input.value && input.value.trim() !== '') {
            if (container) {
                container.classList.remove('d-none');
            }
            // Formata o valor usando IMask
            IMask(input, {
                mask: "R$ num",
                blocks: {
                    num: {
                        mask: Number,
                        thousandsSeparator: ".",
                        radix: ",",
                        mapToRadix: ["."],
                        scale: 2,
                        padFractional: true
                    }
                }
            });
        }

        // Aplica máscaras em campos PA e ND que já possuem valores
        if (container) {
            container.querySelectorAll('.pa-input, .nd-input').forEach(field => {
                if (field.value) {
                    const maskType = field.classList.contains('pa-input') ? '00.000.000.000' : '0.0.00.00';
                    criarMascara(field, maskType);
                }
            });
        }
        });

    // Adiciona validação visual para todos os campos required
    document.querySelectorAll('input[required], select[required], textarea[required]').forEach(function(element) {
        element.addEventListener('invalid', function(e) {
            e.preventDefault();
            this.classList.add('is-invalid');
            
            // Adiciona mensagem de erro se não existir
            let errorDiv = this.nextElementSibling;
            if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = 'Este campo é obrigatório';
                this.parentNode.insertBefore(errorDiv, this.nextSibling);
            }
        });
        
        element.addEventListener('input', function() {
            if (this.validity.valid) {
                this.classList.remove('is-invalid');
                // Remove mensagem de erro se existir
                const errorDiv = this.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                    errorDiv.remove();
                }
            }
        });
    });

    // Inicializa todas as máscaras ao carregar a página
    aplicarMascaras();

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
                <input type="text" name="${tipo}_despesas[${index}]numero_pa]" class="form-control pa-input" placeholder="00.000.000.000">
            </div>
            <div class="col-md-12">
                <label>Natureza Despesa</label>
                <input type="text" name="${tipo}_despesas[${index}][natureza_despesa]" class="form-control nd-input" placeholder="0.0.00.00">
                <select name="${tipo}_despesas[${index}][natureza_despesa]" class="form-control">
                    <option value="">Selecione um PA</option>
                    <option value="1.1.11.11">1.1.11.11</option>
                    <option value="2.2.22.22">2.2.22.22</option>
                    <option value="3.3.33.33">3.3.33.33</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-danger mt-4" onclick="removerDespesa(this)">Remover</button>
            </div>
        </div>
    `;
    container.appendChild(div);
    // Aplicar máscaras imediatamente após adicionar os campos
    aplicarMascaras();
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
                    <label class="form-label">Empresa</label>
                    <input type="text" class="form-control" name="contratos[${contratoIndex}][nome_empresa_contrato]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">CNPJ</label>
                    <input type="text" class="form-control cnpj-input" name="contratos[${contratoIndex}][cnpj_contrato]">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Número de Telefone</label>
                    <input type="text" class="form-control phone-input" name="contratos[${contratoIndex}][numero_telefone_contrato]">
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
                    <textarea class="form-control" name="contratos[${contratoIndex}][observacoes]" rows="2"></textarea>
                </div>
                <div class="col-12 text-end">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removerContrato(${contratoIndex})">Excluir</button>
                </div>
            </div>
        </div>
    `;
    document.getElementById('contratos').insertAdjacentHTML('beforeend', contratoHTML);

    // Aplicar máscaras
    aplicarMascaras();

    // Tornar os inputs de data clicáveis e focáveis
    document.querySelectorAll("input[type='date']").forEach(input => {
        input.addEventListener("focus", function () {
            this.showPicker(); // Abre automaticamente o seletor de data
        });
    });
}

function aplicarMascaras() {
    // Função auxiliar para criar máscara
    function criarMascara(input, mascara) {
        // Define o placeholder com o formato completo
        const placeholder = mascara.replace(/0/g, '0');
        input.setAttribute('placeholder', placeholder);
        
        // Cria a máscara com o IMask
        input.setAttribute('maxlength', mascara.length);
        let maskInstance = IMask(input, {
            mask: mascara,
            lazy: true,
            placeholderChar: '0',
            prepare: (str) => {
                return str.replace(/[^\d]/g, '');
            },
            beforeInput: (value, cb) => {
                if (maskInstance && !value) {
                    maskInstance.updateOptions({
                        lazy: false
                    });
                }
                cb(value);
            }
        });
        
        const maskRef = maskInstance;

        // Eventos ao focar no campo
        input.addEventListener('focus', function() {
            this.selectionStart = this.selectionEnd = 0;
            if (!this.value) {
                maskRef.updateValue();
            }
            requestAnimationFrame(() => {
                if (this.value.length > 0) {
                    this.setSelectionRange(0, 0);
                }
            });
        });

        // Eventos para gerenciar o placeholder
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.placeholder = placeholder;
            }
        });

        // Atualiza valor quando colar
        input.addEventListener('paste', function(e) {
            setTimeout(() => maskRef.updateValue(), 0);
        });

        // Previne caracteres inválidos
        input.addEventListener('keypress', function(e) {
            if (!/\d/.test(e.key)) {
                e.preventDefault();
            }
        });

        return maskRef;
    }

    // Máscara para Número PA
    document.querySelectorAll(".pa-input").forEach(input => {
        criarMascara(input, '00.000.0000.0000');
    });

    // Máscara para Natureza Despesa
    document.querySelectorAll(".nd-input").forEach(input => {
        criarMascara(input, '0.0.00.00');
    });

    // Máscara de Número do Processo
    document.querySelectorAll("#numero_processo").forEach(input => {
        criarMascara(input, '0000.000000/0000-00');
    });

    // Máscara de CNPJ
    document.querySelectorAll(".cnpj-input").forEach(input => {
        criarMascara(input, '00.000.000/0000-00');
    });

    // Máscara de Número de Telefone
    document.querySelectorAll(".phone-input").forEach(input => {
        criarMascara(input, '(00) 00000-0000');
    });

    // Máscara de Valores Monetários
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
}

function removerContrato(contratoIndex) {
    document.getElementById(`contrato_${contratoIndex}`).remove();
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
