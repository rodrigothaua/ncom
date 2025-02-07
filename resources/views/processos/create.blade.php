<!-- resources/views/processos/create.blade.php -->
@extends('layouts.app')

@if (request()->is('processos*'))
@include('layouts.navbar')
@endif

@section('content')
@include('layouts.partials.alerts')
<div class="container">
    <h1 class="my-4">Cadastrar Novo Processo</h1>

    <!-- Formulário de cadastro de novo processo -->
    <form action="{{ route('processos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="numero" class="form-label">Número do Processo</label>
            <input type="text" class="form-control" id="numero_processo" name="numero_processo"
                placeholder="Digite o número do processo" required>
        </div>

        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="4"
                placeholder="Informe resumidamente a descrição do processo" required>
            </textarea>
        </div>

        <div class="mb-3">
            <label for="requisitante" class="form-label">Requisitante</label>
            <input type="text" name="requisitante" class="form-control" required>
        </div>

        <!-- Checkboxes de Categorias -->
        <div class="mb-3">
            <label class="form-label">Categoria</label>
            <div>
                <input type="checkbox" name="categoria[]" value="consumo" class="form-check-input"> Consumo
                <input type="checkbox" name="categoria[]" value="permanente" class="form-check-input"> Permanente
                <input type="checkbox" name="categoria[]" value="servico" class="form-check-input"> Serviço
            </div>
        </div>

        <hr>

        <!-- Indeterminate Checkbox -->
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="indeterminateCheckbox">
            <label class="form-check-label">Informar valores e datas</label>
        </div>

        <!-- Campos Opcionais -->
        <div id="optionalFields" style="display: none;">
            <div class="mb-3">
                <label for="valor_total" class="form-label">Valor Total</label>
                <input type="number" class="form-control" id="valor_total" name="valor_total" step="0.01">
            </div>

            <div class="mb-3">
                <label for="data_inicio" class="form-label">Data de Início</label>
                <input type="date" class="form-control" id="data_inicio" name="data_inicio">
            </div>

            <div class="mb-3">
                <label for="data_vencimento" class="form-label">Data de Vencimento</label>
                <input type="date" class="form-control" id="data_vencimento" name="data_vencimento">
            </div>
        </div>

        <br>

        <button type="submit" class="btn btn-success">Cadastrar</button>
        <a href="{{ route('processos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection