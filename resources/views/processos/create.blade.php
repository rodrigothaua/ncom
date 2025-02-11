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
                <input type="text" name="requisitante" class="form-control" placeholder="Ex: SUPEL, NCOM..." required>
            </div>
        </div><br>

        <!-- Checkboxes de Categorias -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Valor de processo por Categoria</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label>Consumo</label>
                        <input type="text" class="form-control currency" name="valor_consumo" id="valor_consumo" placeholder="R$ 0,00">
                    </div>
                    <div class="col-md-3">
                        <label>Permanente</label>
                        <input type="text" class="form-control currency" name="valor_permanente" id="valor_permanente" placeholder="R$ 0,00">
                    </div>
                    <div class="col-md-3">
                        <label>Serviço</label>
                        <input type="text" class="form-control currency" name="valor_servico" id="valor_servico" placeholder="R$ 0,00">
                    </div>
                    <div class="col-md-3">
                        <label>Valor Total</label>
                        <input type="text" class="form-control currency" id="valor_total" name="valor_total" placeholder="R$ 0,00" disabled readonly>
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
        <!-- Campos Opcionais -->
        <div id="optionalFields" style="display: none;">

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
                </div>
            </div>
        </div>

        <br>

        <button type="submit" class="btn btn-success">Cadastrar Processo</button>
        <a href="{{ route('processos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection