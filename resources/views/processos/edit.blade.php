@extends('layouts.app')

@if (request()->is('processos*')) 
    @include('layouts.navbar')
@endif

@section('content')
<div class="container">
    <h1>Editar Processo</h1>
    <form action="{{ route('processos.update', $processo->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nome" class="form-label">Número do Processo</label>
            <input type="text" name="numero_processo" class="form-control" id="numero_processo" value="{{ $processo->numero_processo }}" required>
        </div>
        <div class="mb-3">
            <label for="nome" class="form-label">Descrição do Processo</label>
            <textarea name="descricao" class="form-control" id="descricao" rows="3" required>{{ $processo->descricao }}</textarea>
        </div>
        <div class="mb-3">
            <label for="categoria" class="form-label">Categoria</label>
            <select name="categoria" id="categoria" class="form-control" required>
                <option value="Consumo" {{ $processo->categoria == 'Consumo' ? 'selected' : '' }}>Consumo</option>
                <option value="Permanente" {{ $processo->categoria == 'Permanente' ? 'selected' : '' }}>Permanente</option>
                <option value="Serviço" {{ $processo->categoria == 'Serviço' ? 'selected' : '' }}>Serviço</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="valor_total" class="form-label">Valor Total</label>
            <input type="number" name="valor_total" class="form-control" id="valor_total" value="{{ $processo->valor_total }}" required>
        </div>
        <div class="mb-3">
            <label for="data_vencimento" class="form-label">Data início</label>
            <input type="date" name="data_inicio" class="form-control" id="data_inicio" value="{{ $processo->data_inicio }}" required>
        </div>
        <div class="mb-3">
            <label for="data_vencimento" class="form-label">Data de Vencimento</label>
            <input type="date" name="data_vencimento" class="form-control" id="data_vencimento" value="{{ $processo->data_vencimento }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('processos.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@endsection
