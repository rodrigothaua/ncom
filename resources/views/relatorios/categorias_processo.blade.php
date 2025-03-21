@extends('layouts.app')

@section('title', 'SIGECOM - Relatório de Categorias por Processo')

@section('content')
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Categorias por Processo</h5>
                </div>
                <div class="card-body">
                    @include('relatorios.partials.filtros')
                    
                    @if(request()->getQueryString())
                        <div class="d-flex justify-content-end mb-3">
                            <div class="btn-group">
                                <button onclick="window.print()" class="btn btn-primary">
                                    <i class="bi bi-printer"></i> Imprimir
                                </button>
                                <button type="submit" form="filtroForm" name="export_pdf" value="1" class="btn btn-danger">
                                    <i class="bi bi-file-pdf"></i> Exportar PDF
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Número do Processo</th>
                                    <th>Requisitante</th>
                                    <th style="min-width: 200px">Descrição</th>
                                    <th>Consumo</th>
                                    <th>Permanente</th>
                                    <th>Serviço</th>
                                    <th>Total</th>
                                    <th class="no-print">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalConsumo = 0;
                                    $totalPermanente = 0;
                                    $totalServico = 0;
                                @endphp
                                @forelse($processos as $processo)
                                    @php
                                        $totalConsumo += $processo->categorias->valor_consumo ?? 0;
                                        $totalPermanente += $processo->categorias->valor_permanente ?? 0;
                                        $totalServico += $processo->categorias->valor_servico ?? 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $processo->numero_processo }}</td>
                                        <td>{{ $processo->requisitante }}</td>
                                        <td>{{ $processo->descricao }}</td>
                                        <td class="text-end">R$ {{ number_format($processo->categorias->valor_consumo ?? 0, 2, ',', '.') }}</td>
                                        <td class="text-end">R$ {{ number_format($processo->categorias->valor_permanente ?? 0, 2, ',', '.') }}</td>
                                        <td class="text-end">R$ {{ number_format($processo->categorias->valor_servico ?? 0, 2, ',', '.') }}</td>
                                        <td class="text-end">
                                            <strong>R$ {{ number_format(
                                                ($processo->categorias->valor_consumo ?? 0) +
                                                ($processo->categorias->valor_permanente ?? 0) +
                                                ($processo->categorias->valor_servico ?? 0),
                                                2, ',', '.'
                                            ) }}</strong>
                                        </td>
                                        <td class="no-print">
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detalhesModal{{ $processo->id }}">
                                                <i class="bi bi-eye"></i> Detalhes
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Nenhum processo encontrado com os filtros aplicados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <td colspan="3" class="text-end"><strong>Totais:</strong></td>
                                    <td class="text-end"><strong>R$ {{ number_format($totalConsumo, 2, ',', '.') }}</strong></td>
                                    <td class="text-end"><strong>R$ {{ number_format($totalPermanente, 2, ',', '.') }}</strong></td>
                                    <td class="text-end"><strong>R$ {{ number_format($totalServico, 2, ',', '.') }}</strong></td>
                                    <td class="text-end"><strong>R$ {{ number_format($totalConsumo + $totalPermanente + $totalServico, 2, ',', '.') }}</strong></td>
                                    <td class="no-print"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                        @if($processos->isNotEmpty())
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Distribuição por Categoria</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="categoriasChart" style="height: 300px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Resumo de Valores</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tr>
                                                        <td>Total Consumo:</td>
                                                        <td class="text-end">R$ {{ number_format($totalConsumo, 2, ',', '.') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Permanente:</td>
                                                        <td class="text-end">R$ {{ number_format($totalPermanente, 2, ',', '.') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Serviço:</td>
                                                        <td class="text-end">R$ {{ number_format($totalServico, 2, ',', '.') }}</td>
                                                    </tr>
                                                    <tr class="table-primary">
                                                        <td><strong>Total Geral:</strong></td>
                                                        <td class="text-end"><strong>R$ {{ number_format($totalConsumo + $totalPermanente + $totalServico, 2, ',', '.') }}</strong></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @push('scripts')
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                var ctx = document.getElementById('categoriasChart').getContext('2d');
                                new Chart(ctx, {
                                    type: 'pie',
                                    data: {
                                        labels: ['Consumo', 'Permanente', 'Serviço'],
                                        datasets: [{
                                            data: [{{ $totalConsumo }}, {{ $totalPermanente }}, {{ $totalServico }}],
                                            backgroundColor: [
                                                'rgba(255, 99, 132, 0.8)',
                                                'rgba(54, 162, 235, 0.8)',
                                                'rgba(255, 206, 86, 0.8)'
                                            ]
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false
                                    }
                                });
                            });
                            </script>
                            @endpush
                        @endif
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($processos as $processo)
    <!-- Modal de Detalhes -->
    <div class="modal fade" id="detalhesModal{{ $processo->id }}" tabindex="-1" aria-labelledby="detalhesModalLabel{{ $processo->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalhesModalLabel{{ $processo->id }}">Detalhes do Processo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Número do Processo:</strong> {{ $processo->numero_processo }}
                        </div>
                        <div class="col-md-6">
                            <strong>Requisitante:</strong> {{ $processo->requisitante }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Descrição:</strong>
                            <p>{{ $processo->descricao }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Valores por Categoria:</strong>
                            <ul class="list-unstyled">
                                <li>Consumo: R$ {{ number_format($processo->categorias->valor_consumo ?? 0, 2, ',', '.') }}</li>
                                <li>Permanente: R$ {{ number_format($processo->categorias->valor_permanente ?? 0, 2, ',', '.') }}</li>
                                <li>Serviço: R$ {{ number_format($processo->categorias->valor_servico ?? 0, 2, ',', '.') }}</li>
                                <li><strong>Total: R$ {{ number_format(
                                    ($processo->categorias->valor_consumo ?? 0) +
                                    ($processo->categorias->valor_permanente ?? 0) +
                                    ($processo->categorias->valor_servico ?? 0),
                                    2, ',', '.'
                                ) }}</strong></li>
                            </ul>
                        </div>
                    </div>
                    @if($processo->categorias && $processo->categorias->detalhesDespesa)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Números PA/ND:</strong>
                                <ul class="list-unstyled">
                                    @php $detalhes = $processo->categorias->detalhesDespesa; @endphp
                                    @if($detalhes->pa_consumo)<li>PA Consumo: {{ $detalhes->pa_consumo }}</li>@endif
                                    @if($detalhes->pa_permanente)<li>PA Permanente: {{ $detalhes->pa_permanente }}</li>@endif
                                    @if($detalhes->pa_servico)<li>PA Serviço: {{ $detalhes->pa_servico }}</li>@endif
                                    @if($detalhes->nd_consumo)<li>ND Consumo: {{ $detalhes->nd_consumo }}</li>@endif
                                    @if($detalhes->nd_permanente)<li>ND Permanente: {{ $detalhes->nd_permanente }}</li>@endif
                                    @if($detalhes->nd_servico)<li>ND Serviço: {{ $detalhes->nd_servico }}</li>@endif
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<style>
    @media print {
        .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
        }
    }
    .table td {
        vertical-align: middle;
        word-wrap: break-word;
        max-width: 300px;
        padding: 15px 10px;
    }
</style>

@endsection
