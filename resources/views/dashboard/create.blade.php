@extends('layouts.app')

@section('title', 'Novo Processo')

@section('content')
<h1 class="mb-4">Novo Processo</h1>

<form action="{{ route('dashboard.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" name="nome" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="categoria" class="form-label">Categoria</label>
        <select name="categoria" class="form-select" required>
            <option value="consumo">Consumo</option>
            <option value="permanente">Permanente</option>
            <option value="servico">Serviço</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="valor_total" class="form-label">Valor Total</label>
        <input type="number" step="0.01" name="valor_total" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="data_inicio" class="form-label">Data Início</label>
        <input type="date" name="data_inicio" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="data_fim" class="form-label">Data Fim</label>
        <input type="date" name="data_fim" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Salvar</button>
</form>
@endsection
