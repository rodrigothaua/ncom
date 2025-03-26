@extends('layouts.app')

@section('title', 'Editar processo - SIGECOM')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
@include('layouts.partials.alerts')
<div class="container-fluid">
    <h1 class="my-4">Editar Processo</h1>

    <form action="{{ route('processos.update', $processo->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Dados do processo</h5>
                <div class="row">
                    <div class="col-md-4">
                        <div class="col">
                            <label for="">Número do Processo</label>
                            <input type="text" class="form-control" id="numero_processo" name="numero_processo"
                                placeholder="0000.000000/0000-00" required maxlength="20"
                                value="{{ $processo->numero_processo }}">
                        </div>
                        <div class="col">
                            <label for="">Data de Entrada</label>
                            <input type="date" class="form-control" id="data_entrada" name="data_entrada" required
                                value="{{ $processo->data_entrada }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="">Requisitante</label>
                        <select class="form-select" name="requisitante" id="requisitante" required>
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
                    <div class="col-md-4">
                        <label for="">Descrição do processo</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="4"
                            placeholder="Informe resumidamente a descrição do processo"
                            required>{{ $processo->descricao }}</textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <h5 class="card-title">Valor de processo por Categoria</h5>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label>Consumo</label>
                            <input type="text" name="valor_consumo" id="valor_consumo" class="form-control money"
                                placeholder="R$0,00" oninput="calcularTotal()"
                                value="{{ number_format($processo->categorias->valor_consumo ?? 0, 2, ',', '.') }}">
                            <div id="pa_consumo_container" class="mt-2 {{ $processo->consumo_despesa ? '' : 'd-none' }}">
                                <label for="numero_pa_consumo">Número de PA (Consumo)</label>
                                <input type="text" name="consumo_despesa[numero_pa]" class="form-control pa-input"
                                    placeholder="00.000.0000.0000"
                                    value="{{ $processo->categorias->detalhesDespesa->pa_consumo ?? '' }}">
                                <label for="natureza_despesa_consumo">Natureza da Despesa (Consumo)</label>
                                <input type="text" name="consumo_despesa[natureza_despesa]" class="form-control nd-input"
                                    placeholder="0.0.00.00"
                                    value="{{ $processo->categorias->detalhesDespesa->nd_consumo ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Permanente</label>
                            <input type="text" name="valor_permanente" id="valor_permanente" class="form-control money"
                                placeholder="R$0,00" oninput="calcularTotal()"
                                value="{{ number_format($processo->categorias->valor_permanente ?? 0, 2, ',', '.') }}">
                            <div id="pa_permanente_container" class="mt-2 {{ $processo->permanente_despesa ? '' : 'd-none' }}">
                                <label for="numero_pa_permanente">Número de PA (Permanente)</label>
                                <input type="text" name="permanente_despesa[numero_pa]" class="form-control pa-input"
                                    placeholder="00.000.0000.0000"
                                    value="{{ $processo->categorias->detalhesDespesa->pa_permanente ?? '' }}">
                                <label for="natureza_despesa_permanente">Natureza da Despesa (Permanente)</label>
                                <input type="text" name="permanente_despesa[natureza_despesa]" class="form-control nd-input"
                                    placeholder="0.0.00.00"
                                    value="{{ $processo->categorias->detalhesDespesa->nd_permanente ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Serviço</label>
                            <input type="text" name="valor_servico" id="valor_servico" class="form-control money"
                                placeholder="R$0,00" oninput="calcularTotal()"
                                value="{{ number_format($processo->categorias->valor_servico ?? 0, 2, ',', '.') }}">
                            <div id="pa_servico_container"
                                class="mt-2 {{ $processo->categorias->detalhesDespesa->pa_servico ? '' : 'd-none' }}">
                                <label for="numero_pa_servico">Número de PA (Serviço)</label>
                                <input type="text" name="servico_despesa[numero_pa]" class="form-control pa-input"
                                    placeholder="00.000.0000.0000"
                                    value="{{ $processo->categorias->detalhesDespesa->pa_servico ?? '' }}">
                                <label for="natureza_despesa_servico">Natureza da Despesa (Serviço)</label>
                                <input type="text" name="servico_despesa[natureza_despesa]" class="form-control nd-input"
                                    placeholder="0.0.00.00"
                                    value="{{ $processo->categorias->detalhesDespesa->nd_servico ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Valor Total</label>
                            <input type="text" name="valor_total" id="valor_total" class="form-control"
                                placeholder="R$0,00" disabled readonly value="{{ $processo->valor_total }}">
                        </div>
                    </div>
                </div>
            </div>
        </div><br>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="indeterminateCheckbox"
                {{ $processo->modalidade ? 'checked' : '' }}>
            <label class="form-check-label">Existe contrato?</label>
        </div>

        <br>

        <div id="optionalFields" style="{{ $processo->modalidade ? '' : 'display: none;' }}">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informações do Contrato</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modalidade" class="form-label">Modalidade</label>
                            <select class="form-select" name="modalidade" id="modalidade">
                                <option disabled>Selecione...</option>
                                <option value="PREGÃO" {{ $processo->modalidade == 'PREGÃO' ? 'selected' : '' }}>PREGÃO
                                </option>
                                <option value="CONCORRÊNCIA"
                                    {{ $processo->modalidade == 'CONCORRÊNCIA' ? 'selected' : '' }}>CONCORRÊNCIA</option>
                                <option value="CONCURSO" {{ $processo->modalidade == 'CONCURSO' ? 'selected' : '' }}>CONCURSO
                                </option>
                                <option value="LEILÃO" {{ $processo->modalidade == 'LEILÃO' ? 'selected' : '' }}>LEILÃO
                                </option>
                                <option value="DIÁLOGO COMPETITIVO"
                                    {{ $processo->modalidade == 'DIÁLOGO COMPETITIVO' ? 'selected' : '' }}>DIÁLOGO
                                    COMPETITIVO</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="procedimentos_auxiliares" class="form-label">Procedimentos Auxiliares</label>
                            <select class="form-select" name="procedimentos_auxiliares"
                                id="procedimentos_auxiliares">
                                <option disabled>Selecione...</option>
                                <option value="CREDENCIAMENTO"
                                    {{ $processo->procedimentos_auxiliares == 'CREDENCIAMENTO' ? 'selected' : '' }}>
                                    CREDENCIAMENTO</option>
                                <option value="PRÉ-QUALIFICADO"
                                    {{ $processo->procedimentos_auxiliares == 'PRÉ-QUALIFICADO' ? 'selected' : '' }}>
                                    PRÉ-QUALIFICADO</option>
                                <option value="PROCEDIMENTO DE MANIFESTAÇÃO DE INTERESSE"
                                    {{ $processo->procedimentos_auxiliares == 'PROCEDIMENTO DE MANIFESTAÇÃO DE INTERESSE' ? 'selected' : '' }}>
                                    PROCEDIMENTO DE MANIFESTAÇÃO DE INTERESSE</option>
                                <option value="SISTEMA DE REGISTRO DE PREÇÕS"
                                    {{ $processo->procedimentos_auxiliares == 'SISTEMA DE REGISTRO DE PREÇÕS' ? 'selected' : '' }}>
                                    SISTEMA DE REGISTRO DE PREÇÕS</option>
                                <option value="REGISTRO CADASTRAL"
                                    {{ $processo->procedimentos_auxiliares == 'REGISTRO CADASTRAL' ? 'selected' : '' }}>
                                    REGISTRO CADASTRAL</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Contratos</h5>
                    <div class="container mt-4">
                        <h3>Adicionar Contratos</h3>
                        <div id="contratos" class="mb-3">
                            @foreach($processo->contratos as $index => $contrato)
                                <div class="contrato-item border p-3 mb-2">
                                <h5>Contrato {{ $index + 1 }}</h5>
                                    <input type="hidden" name="contratos[{{ $index }}][id]"
                                        value="{{ $contrato->id }}">
                                    <div class="mb-2">
                                        <label class="form-label">Número do Contrato</label>
                                        <input type="text" name="contratos[{{ $index }}][numero_contrato]"
                                            class="form-control" value="{{ $contrato->numero_contrato }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Valor do Contrato</label>
                                        <input type="text" name="contratos[{{ $index }}][valor_contrato]"
                                            class="form-control money-input" value="{{ $contrato->valor_contrato }}"
                                            required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Data Inicial</label>
                                        <input type="date" name="contratos[{{ $index }}][data_inicial_contrato]"
                                            class="form-control" value="{{ $contrato->data_inicial_contrato }}"
                                            required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Data Final</label>
                                        <input type="date" name="contratos[{{ $index }}][data_final_contrato]"
                                            class="form-control" value="{{ $contrato->data_final_contrato }}"
                                            required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Observações</label>
                                        <input type="text" name="contratos[{{ $index }}][observacao]"
                                            class="form-control" value="{{ $contrato->observacao }}">
                                    </div>
                                    <button type="button" class="btn btn-danger remove-contrato">Remover</button>
                                </div>
                            @endforeach
                        </div>
                        <a type="button" class="icon-link icon-link-hover" onclick="adicionarContrato()">Incluir
                            Contrato <i class="bi bi-plus-circle-dotted"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <button type="submit" class="btn btn-success">Atualizar Processo</button>
        <a href="{{ route('processos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="https://unpkg.com/imask"></script>
<script src="{{ asset('js/create_processo.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let contratoIndex = {{ count($processo->contratos) }};

        function renumerarContratos() {
            const contratos = document.querySelectorAll('.contrato-item');
            contratos.forEach((contrato, index) => {
                contrato.querySelector('h5').textContent = `Contrato ${index + 1}`;
                contrato.querySelector('input[name$="[id]"]').name = `contratos[${index}][id]`;
                contrato.querySelector('input[name$="[numero_contrato]"]').name = `contratos[${index}][numero_contrato]`;
                contrato.querySelector('input[name$="[valor_contrato]"]').name = `contratos[${index}][valor_contrato]`;
                contrato.querySelector('input[name$="[data_inicial_contrato]"]').name = `contratos[${index}][data_inicial_contrato]`;
                contrato.querySelector('input[name$="[data_final_contrato]"]').name = `contratos[${index}][data_final_contrato]`;
                contrato.querySelector('input[name$="[observacao]"]').name = `contratos[${index}][observacao]`;
            });
        }

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-contrato')) {
                event.target.parentElement.remove();
                renumerarContratos();
            }
        });

        renumerarContratos();
        
        // Inicializa as máscaras
        aplicarMascaras();
    });

    // funçção para calcular o valor total
    function calcularTotal() {
        let valorConsumo = parseFloat(document.getElementById('valor_consumo').value.replace(/[^\d,-]/g, '').replace('.', '').replace(',', '.'));
        let valorPermanente = parseFloat(document.getElementById('valor_permanente').value.replace(/[^\d,-]/g, '').replace('.', '').replace(',', '.'));
        let valorServico = parseFloat(document.getElementById('valor_servico').value.replace(/[^\d,-]/g, '').replace('.', '').replace(',', '.'));

        if (isNaN(valorConsumo)) valorConsumo = 0;
        if (isNaN(valorPermanente)) valorPermanente = 0;
        if (isNaN(valorServico)) valorServico = 0;

        let total = valorConsumo + valorPermanente + valorServico;

        document.getElementById('valor_total').value = 'R$' + total.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    window.onload = calcularTotal;
</script>
@endsection
