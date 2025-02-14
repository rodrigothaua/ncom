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
                <h5 class="card-title">Número do Processo</h5>
                <input type="text" class="form-control" id="numero_processo" name="numero_processo"
                placeholder="Digite o número do processo" required onFocus="">
            </div>
        </div><br>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Descrição</h5>
                <textarea class="form-control" id="descricao" name="descricao" rows="4" placeholder="Informe resumidamente a descrição do processo" required></textarea>
            </div>
        </div><br>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Requisitante</h5>
                <select class="form-select" name="requisitante" id="requisitante">
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
                </select>
            </div>
        </div><br>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Data de Entrada</h5>
                <input type="date" class="form-control" id="data_entrada" name="data_entrada" value="{{ old('data_entrada') }}">
            </div>
        </div><br>

        <!-- Checkboxes de Categorias -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Valor de processo por Categoria</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label>Consumo</label>
                        <input type="text" name="valor_consumo" class="form-control money-input" placeholder="R$0,00" oninput="calcularTotal()">
                    </div>
                    <div class="col-md-3">
                        <label>Permanente</label>
                        <input type="text" name="valor_permanente" class="form-control money-input" placeholder="R$0,00" oninput="calcularTotal()">
                    </div>
                    <div class="col-md-3">
                        <label>Serviço</label>
                        <input type="text" name="valor_servico" class="form-control money-input" placeholder="R$0,00" oninput="calcularTotal()">
                    </div>
                    <div class="col-md-3">
                        <label>Valor Total</label>
                        <input type="text" name="valor_total" id="valor_total" class="form-control" placeholder="R$0,00" disabled readonly>
                    </div>
                </div>
            </div>
        </div><br>

        <!-- Indeterminate Checkbox -->
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="indeterminateCheckbox">
            <label class="form-check-label">Prazo contratual</label>
        </div>

        <br>
        <!-- Campos Opcionais 
        <div id="optionalFields" style="display: none;">-->

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Datas</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="data_inicio" class="form-label">Data de Início</label>
                            <input type="date" class="form-control" id="data_inicio" name="data_inicio">
                        </div>

                        <div class="col-md-6">
                            <label for="data_vencimento" class="form-label">Data de Vencimento</label>
                            <input type="date" class="form-control" id="data_vencimento" name="data_vencimento">
                        </div>
                    </div>
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
                            <select class="form-select" name="procedimentos_aux" id="procedimentos_aux">
                                <option selected disabled>Selecione...</option>
                                <option value="PREGÃO">CREDENCIAMENTO</option>
                                <option value="CONCORRÊNCIA">PRÉ-QUALIFICADO</option>
                                <option value="GECONCURSOCONV">PROCEDIMENTO DE MANIFESTAÇÃO DE INTERESSE</option>
                                <option value="LEILÃO">SISTEMA DE REGISTRO DE PREÇÕS</option>
                                <option value="DIÁLOGO COMPETITIVO">REGISTRO CADASTRAL</option>
                            </select>
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
@endsection