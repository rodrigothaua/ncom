<!-- resources/views/processos/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="my-4">Cadastrar Novo Processo</h1>

        <form action="{{ route('processos.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="numero" class="form-label">Número do Processo</label>
                <input type="text" class="form-control" id="numero" name="numero" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="data_vigente" class="form-label">Data Vigente</label>
                <input type="date" class="form-control" id="data_vigente" name="data_vigente" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="verde">Verde</option>
                    <option value="amarelo">Amarelo</option>
                    <option value="vermelho">Vermelho</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Cadastrar</button>
            <a href="{{ route('processos.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
