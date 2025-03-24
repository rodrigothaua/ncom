@extends('layouts.app')

@section('title', 'SIGECOM - Categorias por Processo')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="h3">Categorias por Processo</h2>
            <p>Filtre os processos por valores das categorias.</p>
        </div>
    </div>

    <form action="{{ route('relatorios.categorias.processo.buscar') }}" method="POST" id="searchForm">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Valores por Categoria</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <!-- Consumo -->
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Consumo</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <label class="form-label">Valor Mínimo</label>
                                                    <input type="text" class="form-control money-mask" name="valor_consumo_min" placeholder="R$ 0,00">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label">Valor Máximo</label>
                                                    <input type="text" class="form-control money-mask" name="valor_consumo_max" placeholder="R$ 0,00">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label">Número PA</label>
                                                    <input type="text" class="form-control pa-input" name="pa_consumo" placeholder="00.000.0000.0000">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label">Natureza Despesa</label>
                                                    <input type="text" class="form-control nd-input" name="nd_consumo" placeholder="0.0.00.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Permanente -->
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Permanente</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <label class="form-label">Valor Mínimo</label>
                                                    <input type="text" class="form-control money-mask" name="valor_permanente_min" placeholder="R$ 0,00">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label">Valor Máximo</label>
                                                    <input type="text" class="form-control money-mask" name="valor_permanente_max" placeholder="R$ 0,00">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label">Número PA</label>
                                                    <input type="text" class="form-control pa-input" name="pa_permanente" placeholder="00.000.0000.0000">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label">Natureza Despesa</label>
                                                    <input type="text" class="form-control nd-input" name="nd_permanente" placeholder="0.0.00.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Serviço -->
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Serviço</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <label class="form-label">Valor Mínimo</label>
                                                    <input type="text" class="form-control money-mask" name="valor_servico_min" placeholder="R$ 0,00">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label">Valor Máximo</label>
                                                    <input type="text" class="form-control money-mask" name="valor_servico_max" placeholder="R$ 0,00">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label">Número PA</label>
                                                    <input type="text" class="form-control pa-input" name="pa_servico" placeholder="00.000.0000.0000">
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label">Natureza Despesa</label>
                                                    <input type="text" class="form-control nd-input" name="nd_servico" placeholder="0.0.00.00">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <a href="{{ route('relatorios.categorias.processo') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Limpar Filtros
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Aplicar Filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if(isset($processos) && $processos->count() > 0)
    <form action="{{ route('relatorios.categorias.processo.pdf') }}" method="POST" id="pdfForm">
        @csrf
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Processos Encontrados</h5>
                <div>
                    <button type="submit" class="btn btn-primary" id="downloadSelected" disabled>
                        <i class="bi bi-download"></i> Baixar Selecionados (PDF)
                    </button>
                    <button type="button" class="btn btn-secondary" id="selectAll">
                        <i class="bi bi-check-all"></i> Selecionar Todos
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="checkAll">
                                    </div>
                                </th>
                                <th>Nº Processo</th>
                                <th>Consumo</th>
                                <th>Permanente</th>
                                <th>Serviço</th>
                                <th>Total</th>
                                <th>Data Entrada</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($processos as $processo)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input processo-check" 
                                               name="processos[]" value="{{ $processo->id }}">
                                    </div>
                                </td>
                                <td>{{ $processo->numero_processo }}</td>
                                <td>R$ {{ number_format($processo->categorias->valor_consumo ?? 0, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($processo->categorias->valor_permanente ?? 0, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($processo->categorias->valor_servico ?? 0, 2, ',', '.') }}</td>
                                <td>R$ {{ number_format($processo->valor_total ?? 0, 2, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($processo->data_entrada)->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('processos.show', $processo->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
    @elseif(isset($processos))
        <div class="alert alert-info mt-4">
            Nenhum processo encontrado com os filtros selecionados.
        </div>
    @endif
</div>

<script src="https://unpkg.com/imask"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscaras para campos monetários
    document.querySelectorAll('.money-mask').forEach(function(input) {
        IMask(input, {
            mask: 'R$ num',
            blocks: {
                num: {
                    mask: Number,
                    thousandsSeparator: '.',
                    radix: ',',
                    scale: 2,
                    padFractional: true
                }
            }
        });
    });

    // Máscaras para PA e ND
    document.querySelectorAll('.pa-input').forEach(function(input) {
        IMask(input, {
            mask: '00.000.0000.0000'
        });
    });

    document.querySelectorAll('.nd-input').forEach(function(input) {
        IMask(input, {
            mask: '0.0.00.00'
        });
    });

    // Controle de seleção
    const checkAll = document.getElementById('checkAll');
    const processoChecks = document.querySelectorAll('.processo-check');
    const downloadButton = document.getElementById('downloadSelected');
    const selectAllButton = document.getElementById('selectAll');

    function updateDownloadButton() {
        const checkedCount = document.querySelectorAll('.processo-check:checked').length;
        downloadButton.disabled = checkedCount === 0;
        downloadButton.innerHTML = `<i class="bi bi-download"></i> Baixar Selecionados (${checkedCount})`;
    }

    if(checkAll) {
        checkAll.addEventListener('change', function() {
            processoChecks.forEach(check => check.checked = this.checked);
            updateDownloadButton();
        });
    }

    processoChecks.forEach(check => {
        check.addEventListener('change', function() {
            checkAll.checked = Array.from(processoChecks).every(c => c.checked);
            updateDownloadButton();
        });
    });

    if(selectAllButton) {
        selectAllButton.addEventListener('click', function() {
            const isAllChecked = Array.from(processoChecks).every(c => c.checked);
            processoChecks.forEach(check => check.checked = !isAllChecked);
            checkAll.checked = !isAllChecked;
            updateDownloadButton();
        });
    }

    updateDownloadButton();
});
</script>
@endsection
