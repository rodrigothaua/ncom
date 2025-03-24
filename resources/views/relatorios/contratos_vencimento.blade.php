@extends('layouts.app')

@section('title', 'SIGECOM - Contratos por Vencimento')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="h3">Contratos por Vencimento</h2>
            <p>Filtre os contratos por período de vencimento.</p>
        </div>
    </div>

    <form action="{{ route('relatorios.contratos.vencimento.buscar') }}" method="POST" id="searchForm">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Período de Vigência</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Data Inicial</label>
                                        <input type="date" class="form-control" name="data_inicio" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Data Final</label>
                                        <input type="date" class="form-control" name="data_fim" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <a href="{{ route('relatorios.contratos.vencimento') }}" class="btn btn-secondary">
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
    <form action="{{ route('relatorios.contratos.vencimento.pdf') }}" method="POST" id="pdfForm">
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
                                <th>Data Inicial</th>
                                <th>Data Final</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Dias Restantes</th>
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
                                <td>{{ \Carbon\Carbon::parse($contrato->data_inicial_contrato)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($contrato->data_final_contrato)->format('d/m/Y') }}</td>
                                <td>R$ {{ number_format($contrato->valor_contrato, 2, ',', '.') }}</td>
                                <td>
                                    @php
                                        $hoje = \Carbon\Carbon::now();
                                        $dataFinal = \Carbon\Carbon::parse($contrato->data_final_contrato);
                                        $diasRestantes = $hoje->diffInDays($dataFinal, false);
                                    @endphp
                                    
                                    @if($diasRestantes < 0)
                                        <span class="badge bg-danger">Vencido</span>
                                    @elseif($diasRestantes <= 30)
                                        <span class="badge bg-warning">Vence em menos de 30 dias</span>
                                    @elseif($diasRestantes <= 60)
                                        <span class="badge bg-info">Vence em 30-60 dias</span>
                                    @else
                                        <span class="badge bg-success">Em dia</span>
                                    @endif
                                </td>
                                <td>
                                    @if($diasRestantes < 0)
                                        <span class="text-danger">Vencido há {{ abs($diasRestantes) }} dias</span>
                                    @else
                                        <span class="text-success">{{ $diasRestantes }} dias restantes</span>
                                    @endif
                                </td>
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
            Nenhum contrato encontrado para o período selecionado.
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
