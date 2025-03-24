@extends('layouts.app')

@section('title', 'SIGECOM - Contratos por Vencimento')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="h3">Contratos por Vencimento</h2>
            <p>Filtre os contratos por período de vigência.</p>
        </div>
    </div>

    <form action="{{ route('relatorios.contratos.vencimento.buscar') }}" method="POST" id="searchForm">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Filtros</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label">Data Inicial</label>
                            <input type="date" class="form-control" name="data_inicio" 
                                   value="{{ request('data_inicio') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label">Data Final</label>
                            <input type="date" class="form-control" name="data_fim" 
                                   value="{{ request('data_fim') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">Todos</option>
                                <option value="vencido" {{ request('status') == 'vencido' ? 'selected' : '' }}>Vencidos</option>
                                <option value="proximo" {{ request('status') == 'proximo' ? 'selected' : '' }}>Vencimento Próximo (30 dias)</option>
                                <option value="vigente" {{ request('status') == 'vigente' ? 'selected' : '' }}>Vigentes</option>
                            </select>
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

    @if(isset($contratos))
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
                                <th>Empresa</th>
                                <th>Valor</th>
                                <th>Início</th>
                                <th>Fim</th>
                                <th>Dias Restantes</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contratos as $contrato)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input contrato-check" 
                                               name="contratos[]" value="{{ $contrato->id }}">
                                    </div>
                                </td>
                                <td>{{ $contrato->numero_contrato }}</td>
                                <td>{{ $contrato->nome_empresa_contrato }}</td>
                                <td>R$ {{ number_format($contrato->valor_contrato, 2, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($contrato->data_inicial_contrato)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($contrato->data_final_contrato)->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        $hoje = \Carbon\Carbon::now();
                                        $dataFinal = \Carbon\Carbon::parse($contrato->data_final_contrato);
                                        $diasRestantes = $hoje->diffInDays($dataFinal, false);
                                    @endphp
                                    {{ $diasRestantes < 0 ? 'Vencido há ' . abs($diasRestantes) . ' dias' : $diasRestantes . ' dias' }}
                                </td>
                                <td>
                                    @if($diasRestantes < 0)
                                        <span class="badge bg-danger">Vencido</span>
                                    @elseif($diasRestantes <= 30)
                                        <span class="badge bg-warning">Vence em breve</span>
                                    @else
                                        <span class="badge bg-success">Vigente</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('processos.show', $contrato->processo->id) }}" 
                                           class="btn btn-sm btn-info" title="Ver processo">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('relatorios.contratos.vencimento.detalhes', $contrato->id) }}" 
                                           class="btn btn-sm btn-primary" title="Ver detalhes e gráficos">
                                            <i class="bi bi-graph-up"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Nenhum contrato encontrado.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validação das datas
    const dataInicio = document.querySelector('input[name="data_inicio"]');
    const dataFim = document.querySelector('input[name="data_fim"]');
    const form = document.getElementById('searchForm');

    form.addEventListener('submit', function(e) {
        if (dataInicio.value && dataFim.value) {
            if (dataInicio.value > dataFim.value) {
                e.preventDefault();
                alert('A data inicial não pode ser maior que a data final.');
            }
        }
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
