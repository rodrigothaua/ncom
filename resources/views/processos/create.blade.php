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
                            placeholder="Digite o número do processo" required maxlength="20" oninput="formatarNumeroProcesso(this)">
                        </div>
                        <!-- input Data de Entrada -->
                        <div class="col">
                            <label for="">Data de Entrada</label>
                            <input type="date" class="form-control" id="data_entrada" name="data_entrada" required value="{{ old('data_entrada') }}">
                        </div>
                    </div>
                    <!-- select Requisitante -->
                    <div class="col-md-4">
                        <label for="">Requisitante</label>
                        <select class="form-select" name="requisitante" id="requisitante" required>
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
                            <div id="pa_consumo_container" class="mt-2 d-none">
                                <p><strong>Número de PA</strong></p>
                            </div>
                            <!-- Select -->
                            <div id="select_consumo_container" class="mt-2 d-none">
                                <p><strong>Natureza de dispesa</strong></p>
                                <select id="select_consumo" name="select_consumo" class="form-control">
                                    <option value="">Selecione...</option>
                                    <option value="Opcao1">Opção 1</option>
                                    <option value="Opcao2">Opção 2</option>
                                    <option value="Opcao3">Opção 3</option>
                                </select>
                            </div>
                        </div>
                        <!-- Valor Permanente -->
                        <div class="col-md-3">
                            <label>Permanente</label>
                            <input type="text" name="valor_permanente" id="valor_permanente" class="form-control money" placeholder="R$0,00" oninput="calcularTotal()">
                            <!-- Adicionar PA Permanente -->
                            <div id="pa_permanente_container" class="mt-2 d-none">
                                <p><strong>Número de PA</strong></p>
                            </div>
                            <!-- Select -->
                            <div id="select_permanente_container" class="mt-2 d-none">
                                <p><strong>Natureza de dispesa</strong></p>
                                <select id="select_permanente" name="select_permanente" class="form-control">
                                    <option value="">Selecione...</option>
                                    <option value="Opcao1">Opção 1</option>
                                    <option value="Opcao2">Opção 2</option>
                                    <option value="Opcao3">Opção 3</option>
                                </select>
                            </div>
                        </div>
                        <!-- Valor Serviço -->
                        <div class="col-md-3">
                            <label>Serviço</label>
                            <input type="text" name="valor_servico" id="valor_servico" class="form-control money" placeholder="R$0,00" oninput="calcularTotal()">
                            <!-- adiconar PA Serviço -->
                            <div id="pa_servico_container" class="mt-2 d-none">
                                <p><strong>Número de PA</strong></p>
                            </div>
                            <!-- Select -->
                            <div id="select_servico_container" class="mt-3 d-none">
                                <p><strong>Natureza de dispesa</strong></p>
                                <select id="select_servico" name="select_servico" class="form-control">
                                    <option value="">Selecione...</option>
                                    <option value="Opcao1">Opção 1</option>
                                    <option value="Opcao2">Opção 2</option>
                                    <option value="Opcao3">Opção 3</option>
                                </select>
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

<script src="{{ asset('js/create_processo.js') }}"></script>
@endsection