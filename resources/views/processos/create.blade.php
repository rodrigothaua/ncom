<!-- resources/views/processos/create.blade.php -->
@extends('layouts.app')

@section('title', 'Cadastrar novo processo - SIGECOM')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
@include('layouts.partials.alerts')
<div class="container-fluid">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h1 class="my-4">Cadastrar Novo Processo</h1>

    <!-- Formulário de cadastro de novo processo -->
    <form action="{{ route('processos.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Dados do processo</h5>
                <div class="row">
                    <div class="col-md-4">
                        <!-- Input Número do Processo -->
                        <div class="col">
                            <label for="">Número do Processo</label>
                            <input type="text" class="form-control @error('numero_processo') is-invalid @enderror" 
                                id="numero_processo" 
                                name="numero_processo"
                                placeholder="0000.000000/0000-00" 
                                required 
                                maxlength="19" 
                                value="{{ old('numero_processo') }}">
                            @error('numero_processo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <!-- input Data de Entrada -->
                        <div class="col">
                            <label for="">Data de Entrada</label>
                            <input type="date" class="form-control @error('data_entrada') is-invalid @enderror" 
                                id="data_entrada" 
                                name="data_entrada" 
                                required 
                                value="{{ old('data_entrada') }}">
                            @error('data_entrada')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <!-- select Requisitante -->
                    <div class="col-md-4">
                        <label for="">Requisitante</label>
                        <select class="form-select @error('requisitante') is-invalid @enderror" 
                            name="requisitante" 
                            id="requisitante" 
                            required>
                            @error('requisitante')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <option selected disabled>Selecione...</option>
                            <option value="GEPLAN">GEPLAN</option>
                            <option value="GAB">GAB</option>
                            <option value="GAEX">GAEX</option>
                            <option value="NGESC">NGESC</option>
                            <option value="FUNESP">FUNESP</option>
                            <option value="GETEC">GETEC</option>
                            <option value="GECONV">GECONV</option>
                            <option value="GELOG">GELOG</option>
                            <option value="GEI">GEI</option>
                            <option value="GAVE">GAVE</option>
                            <option value="GRH">GRH</option>
                            <option value="GESPM">GESPM</option>
                            <option value="GISF">GISF</option>
                            <option value="CIOP">CIOP</option>
                            <option value="CAF">CAF</option>
                            <option value="GCI">GCI</option>
                            <option value="PGE">PGE</option>
                            <option value="PM">PM</option>
                            <option value="PC">PC</option>
                            <option value="CBM">CBM</option>
                            <option value="POLITEC">POLITEC</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="">Descrição do processo</label>
                        <textarea class="form-control @error('descricao') is-invalid @enderror" 
                            id="descricao" 
                            name="descricao" 
                            rows="4" 
                            placeholder="Informe resumidamente a descrição do processo" 
                            required>{{ old('descricao') }}</textarea>
                        @error('descricao')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <h5 class="card-title">Valor de processo por Categoria</h5>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label>Consumo</label>
                            <input type="text" 
                                name="valor_consumo" 
                                id="valor_consumo" 
                                class="form-control money @error('valor_consumo') is-invalid @enderror" 
                                placeholder="R$0,00" 
                                value="{{ old('valor_consumo') }}" 
                                oninput="calcularTotal()">
                            @error('valor_consumo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="pa_consumo_container" class="mt-2 d-none">
                                <label for="numero_pa_consumo">Número de PA (Consumo)</label>
                            <input type="text" 
                                name="consumo_despesa[numero_pa]" 
                                class="form-control pa-input @error('consumo_despesa.numero_pa') is-invalid @enderror" 
                                placeholder="00.000.0000.0000" 
                                value="{{ old('consumo_despesa.numero_pa') }}">
                            @error('consumo_despesa.numero_pa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <!-- input natureza de despesa -->
                            <label for="natureza_despesa_consumo">Natureza da Despesa (Consumo)</label>
                            <input type="text" 
                                name="consumo_despesa[natureza_despesa]" 
                                class="form-control nd-input @error('consumo_despesa.natureza_despesa') is-invalid @enderror" 
                                placeholder="0.0.00.00" 
                                value="{{ old('consumo_despesa.natureza_despesa') }}">
                            @error('consumo_despesa.natureza_despesa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Permanente</label>
                            <input type="text" 
                                name="valor_permanente" 
                                id="valor_permanente" 
                                class="form-control money @error('valor_permanente') is-invalid @enderror" 
                                placeholder="R$0,00" 
                                value="{{ old('valor_permanente') }}" 
                                oninput="calcularTotal()">
                            @error('valor_permanente')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="pa_permanente_container" class="mt-2 d-none">
                                <label for="numero_pa_permanente">Número de PA (Permanente)</label>
                            <input type="text" 
                                name="permanente_despesa[numero_pa]" 
                                class="form-control pa-input @error('permanente_despesa.numero_pa') is-invalid @enderror" 
                                placeholder="00.000.0000.0000" 
                                value="{{ old('permanente_despesa.numero_pa') }}">
                            @error('permanente_despesa.numero_pa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <!-- input natureza de despesa -->
                            <label for="natureza_despesa_permanente">Natureza da Despesa (Permanente)</label>
                            <input type="text" 
                                name="permanente_despesa[natureza_despesa]" 
                                class="form-control nd-input @error('permanente_despesa.natureza_despesa') is-invalid @enderror" 
                                placeholder="0.0.00.00" 
                                value="{{ old('permanente_despesa.natureza_despesa') }}">
                            @error('permanente_despesa.natureza_despesa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Serviço</label>
                            <input type="text" 
                                name="valor_servico" 
                                id="valor_servico" 
                                class="form-control money @error('valor_servico') is-invalid @enderror" 
                                placeholder="R$0,00" 
                                value="{{ old('valor_servico') }}" 
                                oninput="calcularTotal()">
                            @error('valor_servico')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="pa_servico_container" class="mt-2 d-none">
                                <label for="numero_pa_servico">Número de PA (Serviço)</label>
                            <input type="text" 
                                name="servico_despesa[numero_pa]" 
                                class="form-control pa-input @error('servico_despesa.numero_pa') is-invalid @enderror" 
                                placeholder="00.000.0000.0000" 
                                value="{{ old('servico_despesa.numero_pa') }}">
                            @error('servico_despesa.numero_pa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <!-- input natureza de despesa -->
                            <label for="natureza_despesa_servico">Natureza da Despesa (Serviço)</label>
                            <input type="text" 
                                name="servico_despesa[natureza_despesa]" 
                                class="form-control nd-input @error('servico_despesa.natureza_despesa') is-invalid @enderror" 
                                placeholder="0.0.00.00" 
                                value="{{ old('servico_despesa.natureza_despesa') }}">
                            @error('servico_despesa.natureza_despesa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Valor Total</label>
                            <input type="text" name="valor_total" id="valor_total" class="form-control" placeholder="R$0,00" disabled readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div><br>

        <!-- Checkboxes de Categorias -->

        <!-- Indeterminate Checkbox -->
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="indeterminateCheckbox">
            <label class="form-check-label">Existe contrato?</label>
        </div>

        <br>
        <!-- Campos Opcionais -->
        <div id="optionalFields" style="display: none;">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informações do Contrato</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modalidade" class="form-label">Modalidade</label>
                            <select class="form-select" name="modalidade" id="modalidade">
                                <option selected disabled>Selecione...</option>
                                <option value="PREGÃO">PREGÃO</option>
                                <option value="CONCORRÊNCIA">CONCORRÊNCIA</option>
                                <option value="CONCURSO">CONCURSO</option>
                                <option value="LEILÃO">LEILÃO</option>
                                <option value="DIÁLOGO COMPETITIVO">DIÁLOGO COMPETITIVO</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="procedimentos_auxiliares" class="form-label">Procedimentos Auxiliares</label>
                            <select class="form-select" name="procedimentos_auxiliares" id="procedimentos_auxiliares">
                                <option selected disabled>Selecione...</option>
                                <option value="CREDENCIAMENTO">CREDENCIAMENTO</option>
                                <option value="PRÉ-QUALIFICADO">PRÉ-QUALIFICADO</option>
                                <option value="PROCEDIMENTO DE MANIFESTAÇÃO DE INTERESSE">PROCEDIMENTO DE MANIFESTAÇÃO DE INTERESSE</option>
                                <option value="SISTEMA DE REGISTRO DE PREÇÕS">SISTEMA DE REGISTRO DE PREÇÕS</option>
                                <option value="REGISTRO CADASTRAL">REGISTRO CADASTRAL</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Contratos</h5>
                    <div class="row g-3">
                        <div class="container mt-4">
                            <h3>Adicionar Contratos</h3>
                            <div id="contratos" class="mb-3"></div>
                            <a type="button" class="icon-link icon-link-hover" onclick="adicionarContrato()">Incluir Contrato <i class="bi bi-plus-circle-dotted"></i></a>
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

<script src="https://unpkg.com/imask"></script>
<script src="{{ asset('js/create_processo.js') }}"></script>
@endsection
