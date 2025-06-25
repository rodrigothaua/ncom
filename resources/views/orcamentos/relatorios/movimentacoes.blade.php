@extends('layouts.app')

@section('title', 'Relatório de Movimentações - SIGECOM')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Relatório de Movimentações</h1>
        <div>
            <a href="{{ route('orcamentos.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('orcamentos.relatorios.movimentacoes') }}" method="GET" class="row align-items-end">
                <div class="col-md-3 mb-3">
                    <label for="data_inicio" class="form-label">Data Início</label>
                    <input type="date" 
                        class="form-control" 
                        id="data_inicio" 
                        name="data_inicio"
                        value="{{ request('data_inicio', $dataInicio) }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label for="data_fim" class="form-label">Data Fim</label>
                    <input type="date" 
                        class="form-control" 
                        id="data_fim" 
                        name="data_fim"
                        value="{{ request('data_fim', $dataFim) }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label for="tipo" class="form-label">Tipo de Movimentação</label>
                    <select name="tipo" id="tipo" class="form-select">
                        <option value="">Todos</option>
                        <option value="Empenho" {{ request('tipo') === 'Empenho' ? 'selected' : '' }}>Empenho</option>
                        <option value="Liquidação" {{ request('tipo') === 'Liquidação' ? 'selected' : '' }}>Liquidação</option>
                        <option value="Pagamento" {{ request('tipo') === 'Pagamento' ? 'selected' : '' }}>Pagamento</option>
                        <option value="Cancelamento" {{ request('tipo') === 'Cancelamento' ? 'selected' : '' }}>Cancelamento</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filtrar
                    </button>
                    <a href="{{ route('orcamentos.relatorios.movimentacoes') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumo -->
    <div class="row mb-4">
        @php
            $totalEmpenhos = $movimentacoes->where('tipo', 'Empenho')->sum('valor');
            $totalLiquidacoes = $movimentacoes->where('tipo', 'Liquidação')->sum('valor');
            $totalPagamentos = $movimentacoes->where('tipo', 'Pagamento')->sum('valor');
        @endphp
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Total Empenhado</h6>
                    <h4 class="card-title">R$ {{ number_format($totalEmpenhos, 2, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Total Liquidado</h6>
                    <h4 class="card-title">R$ {{ number_format($totalLiquidacoes, 2, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2">Total Pago</h6>
                    <h4 class="card-title">R$ {{ number_format($totalPagamentos, 2, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Movimentações -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Tipo</th>
                            <th>Valor</th>
                            <th>Documento</th>
                            <th>Orçamento</th>
                            <th>Categoria</th>
                            <th>Processo</th>
                            <th>Responsável</th>
                            <th>Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimentacoes as $movimentacao)
                        <tr>
                            <td>{{ $movimentacao['data'] }}</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $movimentacao['tipo'] === 'Empenho' ? 'primary' : 
                                    ($movimentacao['tipo'] === 'Liquidação' ? 'info' : 
                                    ($movimentacao['tipo'] === 'Pagamento' ? 'success' : 'danger')) 
                                }}">
                                    {{ $movimentacao['tipo'] }}
                                </span>
                            </td>
                            <td>R$ {{ number_format($movimentacao['valor'], 2, ',', '.') }}</td>
                            <td>{{ $movimentacao['documento'] ?? '-' }}</td>
                            <td>{{ $movimentacao['orcamento'] }}</td>
                            <td>{{ $movimentacao['categoria'] }}</td>
                            <td>
                                @if($movimentacao['processo_id'])
                                    <a href="{{ route('processos.show', $movimentacao['processo_id']) }}" 
                                        target="_blank" 
                                        class="text-decoration-none">
                                        Ver Processo <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $movimentacao['responsavel'] }}</td>
                            <td>{{ $movimentacao['observacoes'] ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Nenhuma movimentação encontrada no período.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
