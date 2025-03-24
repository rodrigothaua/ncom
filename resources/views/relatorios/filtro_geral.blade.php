@extends('layouts.app')

@section('title', 'SIGECOM - Filtro Geral de Relatórios')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2 class="h3">Filtro Geral de Relatórios</h2>
            <p>Utilize os filtros abaixo para uma busca avançada em todos os dados do sistema.</p>
        </div>
    </div>

    <form action="{{ route('relatorios.filtro.geral.buscar') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <!-- Identificação do Processo -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Identificação do Processo</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Número do Processo</label>
                                        <input type="text" class="form-control" id="numero_processo" name="numero_processo" 
                                            placeholder="0000.000000/0000-00">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Requisitante</label>
                                        <select class="form-select" name="requisitante">
                                            <option value="">Selecione...</option>
                                            @foreach($requisitantes as $requisitante)
                                                <option value="{{ $requisitante }}">{{ $requisitante }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Período e Status -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Período</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Data Inicial</label>
                                        <input type="date" class="form-control" name="data_inicio">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Data Final</label>
                                        <input type="date" class="form-control" name="data_fim">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Status do Contrato</h6>
                            </div>
                            <div class="card-body">
                                <select class="form-select" name="status">
                                    <option value="">Todos os Status</option>
                                    <option value="vencido">Vencido</option>
                                    <option value="menos30">Vence em -30 dias</option>
                                    <option value="30a60">Vence entre 30 e 60 dias</option>
                                    <option value="60a90">Vence entre 60 e 90 dias</option>
                                    <option value="90a180">Vence entre 90 e 180 dias</option>
                                    <option value="mais180">Vence em +180 dias</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Valores -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Valores por Categoria</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Consumo</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control money-mask" name="valor_consumo_min" placeholder="R$ 0,00">
                                            <input type="text" class="form-control money-mask" name="valor_consumo_max" placeholder="R$ 0,00">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Permanente</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control money-mask" name="valor_permanente_min" placeholder="R$ 0,00">
                                            <input type="text" class="form-control money-mask" name="valor_permanente_max" placeholder="R$ 0,00">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Serviço</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control money-mask" name="valor_servico_min" placeholder="R$ 0,00">
                                            <input type="text" class="form-control money-mask" name="valor_servico_max" placeholder="R$ 0,00">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PA e ND -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Números PA e ND</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label">Número PA</label>
                                    <input type="text" class="form-control pa-input" name="pa_numero" placeholder="00.000.0000.0000">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Número ND</label>
                                    <input type="text" class="form-control nd-input" name="nd_numero" placeholder="0.0.00.00">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modalidade e Procedimentos -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Modalidade e Procedimentos</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label">Modalidade</label>
                                    <select class="form-select" name="modalidade">
                                        <option value="">Todas as Modalidades</option>
                                        @foreach($modalidades as $modalidade)
                                            <option value="{{ $modalidade }}">{{ $modalidade }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Procedimentos Auxiliares</label>
                                    <select class="form-select" name="procedimentos">
                                        <option value="">Todos os Procedimentos</option>
                                        @foreach($procedimentos as $procedimento)
                                            <option value="{{ $procedimento }}">{{ $procedimento }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <a href="{{ route('relatorios.filtro.geral') }}" class="btn btn-secondary">
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
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Resultados da Pesquisa</h5>
            <a href="{{ request()->fullUrlWithQuery(['export_pdf' => '1']) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-download"></i> Exportar PDF
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
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
                            <td colspan="6" class="text-center">Nenhum resultado encontrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<script src="https://unpkg.com/imask"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar máscaras aos campos
    var numeroProcessoMask = IMask(document.getElementById('numero_processo'), {
        mask: '0000.000000/0000-00'
    });

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
});
</script>
@endsection
