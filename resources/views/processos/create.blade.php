@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Novo Processo de Compras</h1>
    <form action="{{ route('processos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="numero_processo" class="form-label">Número do Processo</label>
            <input type="text" name="numero_processo" id="numero_processo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="data_vigente" class="form-label">Data Vigente</label>
            <input type="date" name="data_vigente" id="data_vigente" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Cadastrar</button>
    </form>
</div>
@endsection
