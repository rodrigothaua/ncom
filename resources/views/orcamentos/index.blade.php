@extends('layouts.app')

@section('title', 'Dashboard de Orçamentos')

@section('content')
<div class="container-fluid">
    <!-- Título e Filtro de Ano -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">Dashboard de Orçamentos</h1>
        </div>
        <div class="col-md-4">
            <form method="GET" action="{{ route('orcamentos.index') }}" class="d-flex">
                <select name="ano" class="form-select me-2">
                    @for ($i = date('Y') - 5; $i <= date('Y') + 5; $i++)
                        <option value="{{ $i }}" {{ $ano == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="row">
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Total em Orçamentos</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-primary">
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">R$ {{ number_format($resumoPorFonte->sum('valor_total'), 2, ',', '.') }}</h1>
                    <div class="mb-0">
                        <span class="text-muted">Distribuídos em {{ $resumoPorFonte->count() }} fontes</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card border-success">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Valor Disponível</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-success">
                                <i class="bi bi-piggy-bank"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">R$ {{ number_format($resumoPorFonte->sum('valor_disponivel'), 2, ',', '.') }}</h1>
                    <div class="mb-0">
                        @php
                            $percentualDisponivel = $resumoPorFonte->sum('valor_total') > 0 
                                ? ($resumoPorFonte->sum('valor_disponivel') / $resumoPorFonte->sum('valor_total')) * 100 
                                : 0;
                        @endphp
                        <span class="text-success">{{ number_format($percentualDisponivel, 1) }}%</span>
                        <span class="text-muted">disponível para uso</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Vencendo em 30 dias</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-warning">
                                <i class="bi bi-clock-history"></i>
                            </div>
                        </div>
                    </div>
                    <h1 class="mt-1 mb-3">{{ $orcamentosVencendo->count() }}</h1>
                    <div class="mb-0">
                        <span class="text-warning">R$ {{ number_format($orcamentosVencendo->sum('valor_disponivel'), 2, ',', '.') }}</span>
                        <span class="text-muted">disponíveis</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card border-info">
                <div class="card-body">
                    <div class="row">
                        <div class="col mt-0">
                            <h5 class="card-title">Em Execução</h5>
                        </div>
                        <div class="col-auto">
                            <div class="stat text-info">
                                <i class="bi bi-gear"></i>
                            </div>
                        </div>
                    </div>
                    @php
                        $emExecucao = $resumoPorStatus->firstWhere('status', 'Em Execução');
                    @endphp
                    <h1 class="mt-1 mb-3">{{ $emExecucao ? $emExecucao['quantidade'] : 0 }}</h1>
                    <div class="mb-0">
                        <span class="text-info">R$ {{ number_format($emExecucao ? $emExecucao['valor_total'] : 0, 2, ',', '.') }}</span>
                        <span class="text-muted">em andamento</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumo por Fonte -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Orçamentos por Fonte</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fonte</th>
                                    <th>Tipo</th>
                                    <th>Valor Total</th>
                                    <th>Utilizado</th>
                                    <th>Disponível</th>
                                    <th>% Utilizado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resumoPorFonte as $fonte)
                                <tr>
                                    <td>{{ $fonte['fonte'] }}</td>
                                    <td>{{ $fonte['tipo'] }}</td>
                                    <td>R$ {{ number_format($fonte['valor_total'], 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($fonte['valor_utilizado'], 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($fonte['valor_disponivel'], 2, ',', '.') }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $fonte['percentual_utilizado'] > 90 ? 'bg-danger' : ($fonte['percentual_utilizado'] > 70 ? 'bg-warning' : 'bg-success') }}" 
                                                role="progressbar" 
                                                style="width: {{ $fonte['percentual_utilizado'] }}%"
                                                aria-valuenow="{{ $fonte['percentual_utilizado'] }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                {{ number_format($fonte['percentual_utilizado'], 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumo por Status -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Orçamentos por Status</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Quantidade</th>
                                    <th>Valor Total</th>
                                    <th>Utilizado</th>
                                    <th>Disponível</th>
                                    <th>% Utilizado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resumoPorStatus as $status)
                                <tr>
                                    <td>{{ $status['status'] }}</td>
                                    <td>{{ $status['quantidade'] }}</td>
                                    <td>R$ {{ number_format($status['valor_total'], 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($status['valor_utilizado'], 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($status['valor_disponivel'], 2, ',', '.') }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $status['percentual_utilizado'] > 90 ? 'bg-danger' : ($status['percentual_utilizado'] > 70 ? 'bg-warning' : 'bg-success') }}" 
                                                role="progressbar" 
                                                style="width: {{ $status['percentual_utilizado'] }}%"
                                                aria-valuenow="{{ $status['percentual_utilizado'] }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                {{ number_format($status['percentual_utilizado'], 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orçamentos Vencendo -->
    @if($orcamentosVencendo->isNotEmpty())
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">Orçamentos Próximos do Vencimento</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Fonte</th>
                                    <th>Valor Total</th>
                                    <th>Disponível</th>
                                    <th>Vencimento</th>
                                    <th>Dias Restantes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orcamentosVencendo as $orcamento)
                                <tr>
                                    <td>
                                        <a href="{{ route('orcamentos.detalhes', $orcamento['id']) }}">
                                            {{ $orcamento['numero_orcamento'] }}
                                        </a>
                                    </td>
                                    <td>{{ $orcamento['fonte'] }}</td>
                                    <td>R$ {{ number_format($orcamento['valor_total'], 2, ',', '.') }}</td>
                                    <td>R$ {{ number_format($orcamento['valor_disponivel'], 2, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($orcamento['data_fim'])->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge {{ $orcamento['dias_restantes'] <= 7 ? 'bg-danger' : ($orcamento['dias_restantes'] <= 15 ? 'bg-warning' : 'bg-info') }}">
                                            {{ $orcamento['dias_restantes'] }} dias
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
