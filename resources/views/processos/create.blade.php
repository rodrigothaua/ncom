<!-- resources/views/processos/create.blade.php -->
@extends('layouts.app')

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
                <input type="text" class="form-control" id="numero_processo" name="numero_processo" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
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
