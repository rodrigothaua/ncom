<!-- resources/views/processos/create.blade.php -->
@extends('layouts.app')

@if (request()->is('processos*'))
@include('layouts.navbar')
@endif

@section('content')
@include('layouts.partials.alerts')
<div class="container-fluid">
    <h1 class="my-4">Cadastrar Novo Processo</h1>

    <!-- Formulário de cadastro de novo processo -->
    <form action="{{ route('processos.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Dados do processo</h5>
                <div class="row">
                    <div class="col-md-4">
                        <!-- Input Número do Processo -->
                        <div class="col">
                            <label for="">Número do Processo</label>
                            <input type="text" class="form-control" id="numero_processo" name="numero_processo"
                            placeholder="Digite o número do processo" required onFocus="">
                        </div>
                        <!-- input Data de Entrada -->
                        <div class="col">
                            <label for="">Data de Entrada</label>
                            <input type="date" class="form-control" id="data_entrada" name="data_entrada" require value="{{ old('data_entrada') }}">
                        </div>
                    </div>
                    <!-- select Requisitante -->
                    <div class="col-md-4">
                        <label for="">Requisitante</label>
                        <select class="form-select" name="requisitante" id="requisitante" require>
                            <option selected disabled>Selecione...</option>
                            <option value="FUNESP">FUNESP</option>
                            <option value="GETEC">GETEC</option>
                            <option value="GECONV">GECONV</option>
                            <option value="GELOG">GELOG</option>
                            <option value="GEI">GEI</option>
                            <option value="GAVE">GAVE</option>
                            <option value="GRH">GRH</option>
                            <option value="GESPM">GESPM</option>
                            <option value="GISF">GISF</option>
                            <option value="CIOP">CIOP</option>
                            <option value="CAF">CAF</option>
                            <option value="GCI">GCI</option>
                            <option value="PGE">PGE</option>
                            <option value="PM">PM</option>
                            <option value="PC">PC</option>
                            <option value="CBM">CBM</option>
                            <option value="POLITEC">POLITEC</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="">Descrição do processo</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="4" placeholder="Informe resumidamente a descrição do processo" required></textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <h5 class="card-title">Valor de processo por Categoria</h5>
                    <div class="row g-3">
                        <!-- Valor Consumo -->
                        <div class="col-md-3">
                            <label>Consumo</label>
                            <input type="text" name="valor_consumo" id="valor_consumo" class="form-control money" placeholder="R$0,00" oninput="calcularTotal()">
                            <!-- Adicionar PA Consumo -->
                            <div id="pa_consumo_container" class="mt-2" style="display: none;">
                                <button type="button" class="btn btn-sm btn-success" onclick="adicionarPA('consumo')">Adicionar PA</button>
                            </div>
                        </div>
                        <!-- Valor Permanente -->
                        <div class="col-md-3">
                            <label>Permanente</label>
                            <input type="text" name="valor_permanente" id="valor_permanente" class="form-control money" placeholder="R$0,00" oninput="calcularTotal()">
                            <!-- Adicionar PA Permanente -->
                            <div id="pa_permanente_container" class="mt-2" style="display: none;">
                                <button type="button" class="btn btn-sm btn-success" onclick="adicionarPA('permanente')">Adicionar PA</button>
                            </div>
                        </div>
                        <!-- Valor Serviço -->
                        <div class="col-md-3">
                            <label>Serviço</label>
                            <input type="text" name="valor_servico" id="valor_servico" class="form-control money" placeholder="R$0,00" oninput="calcularTotal()">
                            <!-- adiconar PA Serviço -->
                            <div id="pa_servico_container" class="mt-2" style="display: none;">
                                <button type="button" class="btn btn-sm btn-success" onclick="adicionarPA('servico')">Adicionar PA</button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Valor Total</label>
                            <input type="text" name="valor_total" id="valor_total" class="form-control" placeholder="R$0,00" disabled readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>

        <!-- Checkboxes de Categorias -->

        <!-- Indeterminate Checkbox -->
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="indeterminateCheckbox">
            <label class="form-check-label">Existe contrato?</label>
        </div>

        <br>
        <!-- Campos Opcionais -->
        <div id="optionalFields" style="display: none;">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Contratos</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="data_inicio" class="form-label">Modalidade</label>
                            <select class="form-select" name="modalidade" id="modalidade">
                                <option selected disabled>Selecione...</option>
                                <option value="PREGÃO">PREGÃO</option>
                                <option value="CONCORRÊNCIA">CONCORRÊNCIA</option>
                                <option value="GECONCURSOCONV">CONCURSO</option>
                                <option value="LEILÃO">LEILÃO</option>
                                <option value="DIÁLOGO COMPETITIVO">DIÁLOGO COMPETITIVO</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="data_inicio" class="form-label">Procedimentos Auxiliares</label>
                            <select class="form-select" name="procedimentos" id="procedimentos">
                                <option selected disabled>Selecione...</option>
                                <option value="PREGÃO">CREDENCIAMENTO</option>
                                <option value="CONCORRÊNCIA">PRÉ-QUALIFICADO</option>
                                <option value="GECONCURSOCONV">PROCEDIMENTO DE MANIFESTAÇÃO DE INTERESSE</option>
                                <option value="LEILÃO">SISTEMA DE REGISTRO DE PREÇÕS</option>
                                <option value="DIÁLOGO COMPETITIVO">REGISTRO CADASTRAL</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3">
                    <div class="container mt-4">
                        <h3>Adicionar Contratos</h3>
                        <div id="contratos" class="mb-3"></div>

                        <a type="button" class="icon-link icon-link-hover" onclick="adicionarContrato()">Incluir Contrato <i class="bi bi-plus-circle-dotted"></i></a>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <button type="submit" class="btn btn-success">Cadastrar Processo</button>
        <a href="{{ route('processos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
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
                        <textarea class="form-control" name="contratos[${contratoIndex}][obs]" rows="2"></textarea>
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
        ['consumo', 'permanente', 'servico'].forEach(tipo => {
            document.getElementById(`valor_${tipo}`).addEventListener('input', function () {
                let container = document.getElementById(`pa_${tipo}_container`);
                if (this.value.trim() !== '') {
                    container.style.display = 'block';
                } else {
                    container.style.display = 'none';
                    container.innerHTML = '<button type="button" class="btn btn-sm btn-success" onclick="adicionarPA(\'' + tipo + '\')">Adicionar PA</button>';
                }
            });
        });
    });

    function adicionarPA(tipo) {
        let container = document.getElementById(`pa_${tipo}_container`);
        let inputPA = document.createElement('div');
        inputPA.classList.add('input-group', 'mt-2');

        // Criando o campo de input com máscara
        let input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control pa-input';
        input.name = `pa_${tipo}[]`;
        input.placeholder = "Número do PA";
        
        // Aplicando a máscara IMask
        setTimeout(() => {
            IMask(input, {
                mask: '0.0.00.00'
            });
        }, 100);

        // Criando botão de remoção
        let btnRemove = document.createElement('button');
        btnRemove.type = 'button';
        btnRemove.className = 'btn btn-danger btn-sm';
        btnRemove.textContent = 'Remover';
        btnRemove.onclick = function () {
            inputPA.remove();
        };

        inputPA.appendChild(input);
        inputPA.appendChild(btnRemove);
        container.appendChild(inputPA);
    }


</script>
@endsection