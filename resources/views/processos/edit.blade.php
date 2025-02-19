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
            <label for="nome" class="form-label">Requisitante</label>
            <input type="text" name="requisitante" class="form-control" value="{{ $processo->requisitante }}" required>
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
            <label class="form-label">Contratos</label>
            <div id="contratos-container">
                @foreach($processo->contratos as $index => $contrato)
                    <div class="contrato-item border p-3 mb-2">
                        <input type="hidden" name="contratos[{{ $index }}][id]" value="{{ $contrato->id }}">
                        <div class="mb-2">
                            <label class="form-label">Número do Contrato</label>
                            <input type="text" name="contratos[{{ $index }}][numero_contrato]" class="form-control" value="{{ $contrato->numero_contrato }}" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Valor do Contrato</label>
                            <input type="text" name="contratos[{{ $index }}][valor_contrato]" class="form-control money" value="{{ $contrato->valor_contrato }}" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Data Inicial</label>
                            <input type="date" name="contratos[{{ $index }}][data_inicial_contrato]" class="form-control" value="{{ $contrato->data_inicial_contrato }}" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Data Final</label>
                            <input type="date" name="contratos[{{ $index }}][data_final_contrato]" class="form-control" value="{{ $contrato->data_final_contrato }}" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Observações</label>
                            <input type="text" name="contratos[{{ $index }}][obs]" class="form-control" value="{{ $contrato->obs }}">
                        </div>
                        <button type="button" class="btn btn-danger remove-contrato">Remover</button>
                    </div>
                @endforeach
            </div>
            <button type="button" id="add-contrato" class="btn btn-success mt-2">Adicionar Contrato</button>
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
