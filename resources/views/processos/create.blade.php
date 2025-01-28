<!-- resources/views/processos/create.blade.php -->
@extends('layouts.app')

@if (request()->is('processos*'))
@include('layouts.navbar')
@endif

@section('content')
<div class="container">
    <h1 class="my-4">Cadastrar Novo Processo</h1>

    <!-- Exibe mensagens de sucesso ou erro -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

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
                placeholder="Informe resumidamente a descrição do processo" required></textarea>

        </div>

        <div class="mb-3">
            <label for="categoria" class="form-label">Categoria</label>
            <select class="form-select" name="categoria" id="categoria" require>
                <option selected>Selecione a categoria</option>
                <option value="consumo">Consumo</option>
                <option value="permanente">Permanente</option>
                <option value="serviço">Serviço</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="valor_contrato" class="form-label">Valor do contrato</label>
            <input type="number" step="0.01" class="form-control" id="valor_total" name="valor_total"
                placeholder="Digite o valor do contrato" required>
        </div>

        <div class="mb-3">
            <label for="data_inicio">Data de Início</label>
            <input type="date" name="data_inicio" id="data_inicio" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="data_vencimento">Data de Vencimento</label>
            <input type="date" name="data_vencimento" id="data_vencimento" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Cadastrar</button>
        <a href="{{ route('processos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection