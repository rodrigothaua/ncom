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
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Dados do Processo</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label for="numero_processo" class="form-label">Número do Processo</label>
                        <input type="text" name="numero_processo" class="form-control" value="{{ $processo->numero_processo }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="requisitante" class="form-label">Requisitante</label>
                        <select class="form-select" name="requisitante" id="requisitante">
                            <option disabled>Selecione...</option>
                            <option value="FUNESP" {{ $processo->requisitante == 'FUNESP' ? 'selected' : '' }}>FUNESP</option>
                            <option value="GETEC" {{ $processo->requisitante == 'GETEC' ? 'selected' : '' }}>GETEC</option>
                            <option value="GECONV" {{ $processo->requisitante == 'GECONV' ? 'selected' : '' }}>GECONV</option>
                            <option value="GELOG" {{ $processo->requisitante == 'GELOG' ? 'selected' : '' }}>GELOG</option>
                            <option value="GEI" {{ $processo->requisitante == 'GEI' ? 'selected' : '' }}>GEI</option>
                            <option value="GAVE" {{ $processo->requisitante == 'GAVE' ? 'selected' : '' }}>GAVE</option>
                            <option value="GRH" {{ $processo->requisitante == 'GRH' ? 'selected' : '' }}>GRH</option>
                            <option value="GESPM" {{ $processo->requisitante == 'GESPM' ? 'selected' : '' }}>GESPM</option>
                            <option value="GISF" {{ $processo->requisitante == 'GISF' ? 'selected' : '' }}>GISF</option>
                            <option value="CIOP" {{ $processo->requisitante == 'CIOP' ? 'selected' : '' }}>CIOP</option>
                            <option value="CAF" {{ $processo->requisitante == 'CAF' ? 'selected' : '' }}>CAF</option>
                            <option value="GCI" {{ $processo->requisitante == 'GCI' ? 'selected' : '' }}>GCI</option>
                            <option value="PGE" {{ $processo->requisitante == 'PGE' ? 'selected' : '' }}>PGE</option>
                            <option value="PM" {{ $processo->requisitante == 'PM' ? 'selected' : '' }}>PM</option>
                            <option value="PC" {{ $processo->requisitante == 'PC' ? 'selected' : '' }}>PC</option>
                            <option value="CBM" {{ $processo->requisitante == 'CBM' ? 'selected' : '' }}>CBM</option>
                            <option value="POLITEC" {{ $processo->requisitante == 'POLITEC' ? 'selected' : '' }}>POLITEC</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label for="data_inicio" class="form-label">Data de Início</label>
                        <input type="date" name="data_inicio" class="form-control" value="{{ $processo->data_inicio }}">
                    </div>
                    <div class="col-md-4" >
                        <label for="data_vencimento" class="form-label">Data de Vencimento</label>
                        <input type="date" name="data_vencimento" class="form-control" value="{{ $processo->data_vencimento }}">
                    </div>
                    <div class="col-md-4">
                        <label for="data_entrada" class="form-label">Data de Entrada</label>
                        <input type="date" name="data_entrada" class="form-control" value="{{ $processo->data_entrada }}">
                    </div>
                </div>
                <div class="mt-3">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea name="descricao" class="form-control" rows="3" required>{{ $processo->descricao }}</textarea>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="modalidade" class="form-label">Modalidade</label>
                        <select name="modalidade" id="modalidade" class="form-control" required>
                            <option value="Concorrência" {{ $processo->modalidade == 'Concorrência' ? 'selected' : '' }}>Concorrência</option>
                            <option value="Pregão" {{ $processo->modalidade == 'Pregão' ? 'selected' : '' }}>Pregão</option>
                            <option value="Dispensa" {{ $processo->modalidade == 'Dispensa' ? 'selected' : '' }}>Dispensa</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="procedimentos" class="form-label">Procedimentos</label>
                        <input type="text" name="procedimentos" class="form-control" value="{{ $processo->procedimentos }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Valores</h5>
                <div class="row">
                    <div class="col-md-3">
                        <label for="valor_consumo" class="form-label">Valor Consumo</label>
                        <input type="number" name="valor_consumo" class="form-control" step="0.01" value="{{ $processo->valor_consumo }}">
                    </div>
                    <div class="col-md-3">
                        <label for="valor_permanente" class="form-label">Valor Permanente</label>
                        <input type="number" name="valor_permanente" class="form-control money-input" step="0.01" value="{{ $processo->valor_permanente }}">
                    </div>
                    <div class="col-md-3">
                        <label for="valor_servico" class="form-label">Valor Serviço</label>
                        <input type="number" name="valor_servico" class="form-control" step="0.01" value="{{ $processo->valor_servico }}">
                    </div>
                    <div class="col-md-3">
                        <label for="valor_total" class="form-label">Valor Total</label>
                        <input type="number" name="valor_total" class="form-control" step="0.01" value="{{ $processo->valor_total }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Contratos</h5>
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
                                <input type="number" name="contratos[{{ $index }}][valor_contrato]" class="form-control" step="0.01" value="{{ $contrato->valor_contrato }}" required>
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
        </div>

        <button type="submit" class="btn btn-primary mt-3">Atualizar</button>
        <a href="{{ route('processos.index') }}" class="btn btn-secondary mt-3">Voltar</a>
    </form>
</div>

@if($errors->any())
    <div class="alert alert-danger mt-3">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection
