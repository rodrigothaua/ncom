@extends('layouts.app')

@section('title', 'SIGECOM - Filtro Geral')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('relatorios.index') }}">Relatórios</a></li>
                    <li class="breadcrumb-item active">Filtro Geral - 
                        @switch($tipo)
                            @case('processo')
                                Por Processo
                                @break
                            @case('categoria')
                                Por Categoria
                                @break
                            @case('despesa')
                                Por PA/ND
                                @break
                            @case('contrato')
                                Por Contrato
                                @break
                            @default
                                Por Processo
                        @endswitch
                    </li>
                </ol>
            </nav>
            <h2 class="h3">Filtro Geral
                <small class="text-muted">- 
                    @switch($tipo)
                        @case('processo')
                            Por Processo
                            @break
                        @case('categoria')
                            Por Categoria
                            @break
                        @case('despesa')
                            Por PA/ND
                            @break
                        @case('contrato')
                            Por Contrato
                            @break
                        @default
                            Por Processo
                    @endswitch
                </small>
            </h2>
        </div>
    </div>

    <form action="{{ route('relatorios.filtro.geral.buscar') }}" method="POST" id="searchForm">
        @csrf
        <input type="hidden" name="tipo" value="{{ $tipo }}">
        
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <!-- Filtros Base (sempre visíveis) -->
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Filtros Básicos</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Número do Processo</label>
                                        <input type="text" class="form-control" name="numero_processo">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Requisitante</label>
                                        <select class="form-select" name="requisitante">
                                            <option value="">Selecione...</option>
                                            @foreach($requisitantes as $requisitante)
                                                <option value="{{ $requisitante }}">{{ $requisitante }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Modalidade</label>
                                        <select class="form-select" name="modalidade">
                                            <option value="">Selecione...</option>
                                            @foreach($modalidades as $modalidade)
                                                <option value="{{ $modalidade }}">{{ $modalidade }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Procedimentos</label>
                                        <select class="form-select" name="procedimentos">
                                            <option value="">Selecione...</option>
                                            @foreach($procedimentos as $procedimento)
                                                <option value="{{ $procedimento }}">{{ $procedimento }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Data Inicial</label>
                                        <input type="date" class="form-control" name="data_inicio">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Data Final</label>
                                        <input type="date" class="form-control" name="data_fim">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros Específicos -->
                    @if($tipo === 'categoria')
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Filtros por Categoria</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Consumo -->
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header">Consumo</div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Valor Mínimo</label>
                                                        <input type="text" class="form-control money-mask" name="valor_consumo_min">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Valor Máximo</label>
                                                        <input type="text" class="form-control money-mask" name="valor_consumo_max">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Permanente -->
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header">Permanente</div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Valor Mínimo</label>
                                                        <input type="text" class="form-control money-mask" name="valor_permanente_min">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Valor Máximo</label>
                                                        <input type="text" class="form-control money-mask" name="valor_permanente_max">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Serviço -->
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header">Serviço</div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Valor Mínimo</label>
                                                        <input type="text" class="form-control money-mask" name="valor_servico_min">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Valor Máximo</label>
                                                        <input type="text" class="form-control money-mask" name="valor_servico_max">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($tipo === 'despesa')
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Filtros por PA/ND</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- PA -->
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">PA</div>
                                                <div class="card-body">
                                                    @foreach(['consumo', 'permanente', 'servico'] as $tipo)
                                                    <div class="mb-3">
                                                        <label class="form-label">PA {{ ucfirst($tipo) }}</label>
                                                        <input type="text" class="form-control pa-input" name="pa_{{ $tipo }}" placeholder="00.000.0000.0000">
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ND -->
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">ND</div>
                                                <div class="card-body">
                                                    @foreach(['consumo', 'permanente', 'servico'] as $tipo)
                                                    <div class="mb-3">
                                                        <label class="form-label">ND {{ ucfirst($tipo) }}</label>
                                                        <input type="text" class="form-control nd-input" name="nd_{{ $tipo }}" placeholder="0.0.00.00">
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($tipo === 'contrato')
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Filtros por Contrato</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Empresa</label>
                                            <select class="form-select" name="empresa">
                                                <option value="">Selecione...</option>
                                                @foreach($empresas ?? [] as $empresa)
                                                    <option value="{{ $empresa }}">{{ $empresa }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Valor Mínimo</label>
                                            <input type="text" class="form-control money-mask" name="valor_min">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Valor Máximo</label>
                                            <input type="text" class="form-control money-mask" name="valor_max">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <a href="{{ route('relatorios.filtro.geral', ['tipo' => $tipo]) }}" class="btn btn-secondary">
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

    @if(isset($resultados))
    <form action="{{ route('relatorios.filtro.geral.pdf') }}" method="POST" id="pdfForm">
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
                                <th>Requisitante</th>
                                <th>Data Entrada</th>
                                <th>Valor Total</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($resultados as $processo)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input processo-check" 
                                               name="processos[]" value="{{ $processo->id }}">
                                    </div>
                                </td>
                                <td>{{ $processo->numero_processo }}</td>
                                <td>{{ $processo->requisitante }}</td>
                                <td>{{ \Carbon\Carbon::parse($processo->data_entrada)->format('d/m/Y') }}</td>
                                <td>R$ {{ number_format($processo->valor_total, 2, ',', '.') }}</td>
                                <td>
                                    @if($processo->contratos->count() > 0)
                                        @php
                                            $hoje = \Carbon\Carbon::now();
                                            $dataFinal = \Carbon\Carbon::parse($processo->contratos->first()->data_final_contrato);
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
                                    @else
                                        <span class="badge bg-secondary">Sem contrato</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('processos.show', $processo->id) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Nenhum processo encontrado.</td>
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

    // Máscaras para PA
    document.querySelectorAll('.pa-input').forEach(function(input) {
        IMask(input, {
            mask: '00.000.0000.0000'
        });
    });

    // Máscaras para ND
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
