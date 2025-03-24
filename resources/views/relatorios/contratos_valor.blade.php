@extends('layouts.app')

@section('title', 'SIGECOM - Contratos por Valor')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="h3">Contratos por Valor</h2>
            <p>Filtre os contratos por faixa de valor.</p>
        </div>
    </div>

    <form action="{{ route('relatorios.contratos.valor.buscar') }}" method="POST" id="searchForm">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Faixa de Valor do Contrato</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Valor Mínimo</label>
                                        <input type="text" class="form-control money-mask" name="valor_min" placeholder="R$ 0,00">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Valor Máximo</label>
                                        <input type="text" class="form-control money-mask" name="valor_max" placeholder="R$ 0,00">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <a href="{{ route('relatorios.contratos.valor') }}" class="btn btn-secondary">
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

    @if(isset($contratos) && $contratos->count() > 0)
    <form action="{{ route('relatorios.contratos.valor.pdf') }}" method="POST" id="pdfForm">
        @csrf
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Contratos Encontrados</h5>
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
                                <th>Nº Contrato</th>
                                <th>Nº Processo</th>
                                <th>Empresa</th>
                                <th>Valor</th>
                                <th>Data Inicial</th>
                                <th>Data Final</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contratos as $contrato)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input contrato-check" 
                                               name="contratos[]" value="{{ $contrato->id }}">
                                    </div>
                                </td>
                                <td>{{ $contrato->numero_contrato }}</td>
                                <td>{{ $contrato->processo->numero_processo }}</td>
                                <td>{{ $contrato->nome_empresa_contrato }}</td>
                                <td>R$ {{ number_format($contrato->valor_contrato, 2, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($contrato->data_inicial_contrato)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($contrato->data_final_contrato)->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('processos.show', $contrato->processo->id) }}" class="btn btn-sm btn-info">
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
    @elseif(isset($contratos))
        <div class="alert alert-info mt-4">
            Nenhum contrato encontrado para a faixa de valor selecionada.
        </div>
    @endif
</div>

<script src="https://unpkg.com/imask"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscara para campos monetários
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

    // Controle de seleção
    const checkAll = document.getElementById('checkAll');
    const contratoChecks = document.querySelectorAll('.contrato-check');
    const downloadButton = document.getElementById('downloadSelected');
    const selectAllButton = document.getElementById('selectAll');

    function updateDownloadButton() {
        const checkedCount = document.querySelectorAll('.contrato-check:checked').length;
        downloadButton.disabled = checkedCount === 0;
        downloadButton.innerHTML = `<i class="bi bi-download"></i> Baixar Selecionados (${checkedCount})`;
    }

    if(checkAll) {
        checkAll.addEventListener('change', function() {
            contratoChecks.forEach(check => check.checked = this.checked);
            updateDownloadButton();
        });
    }

    contratoChecks.forEach(check => {
        check.addEventListener('change', function() {
            checkAll.checked = Array.from(contratoChecks).every(c => c.checked);
            updateDownloadButton();
        });
    });

    if(selectAllButton) {
        selectAllButton.addEventListener('click', function() {
            const isAllChecked = Array.from(contratoChecks).every(c => c.checked);
            contratoChecks.forEach(check => check.checked = !isAllChecked);
            checkAll.checked = !isAllChecked;
            updateDownloadButton();
        });
    }

    updateDownloadButton();
});
</script>
@endsection
