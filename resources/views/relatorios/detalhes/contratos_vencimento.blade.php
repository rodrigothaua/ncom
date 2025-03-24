@extends('layouts.app')

@section('title', 'SIGECOM - Detalhes do Contrato por Vencimento')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('relatorios.index') }}">Relatórios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('relatorios.contratos.vencimento') }}">Contratos por Vencimento</a></li>
                    <li class="breadcrumb-item active">Detalhes do Contrato</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="h3 mb-0">Detalhes do Contrato {{ $contrato->numero_contrato }}</h2>
            <a href="{{ route('relatorios.contratos.vencimento.detalhes.pdf', $contrato->id) }}" class="btn btn-primary">
                <i class="bi bi-download"></i> Baixar PDF
            </a>
        </div>
    </div>

    <!-- Informações Básicas -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informações do Contrato</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Número do Contrato:</strong></p>
                            <p>{{ $contrato->numero_contrato }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Empresa:</strong></p>
                            <p>{{ $contrato->nome_empresa_contrato }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Valor:</strong></p>
                            <p>R$ {{ number_format($contrato->valor_contrato, 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Data Inicial:</strong></p>
                            <p>{{ \Carbon\Carbon::parse($contrato->data_inicial_contrato)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Data Final:</strong></p>
                            <p>{{ \Carbon\Carbon::parse($contrato->data_final_contrato)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Status:</strong></p>
                            <p>
                                @php
                                    $hoje = \Carbon\Carbon::now();
                                    $dataFinal = \Carbon\Carbon::parse($contrato->data_final_contrato);
                                    $diasRestantes = $hoje->diffInDays($dataFinal, false);
                                @endphp
                                
                                @if($diasRestantes < 0)
                                    <span class="badge bg-danger">Vencido há {{ abs($diasRestantes) }} dias</span>
                                @elseif($diasRestantes <= 30)
                                    <span class="badge bg-warning">Vence em {{ $diasRestantes }} dias</span>
                                @else
                                    <span class="badge bg-success">Vigente por mais {{ $diasRestantes }} dias</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mb-4">
        <!-- Gráfico de Progresso da Vigência -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Progresso da Vigência</h5>
                </div>
                <div class="card-body">
                    <canvas id="progressoChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Cronograma -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Linha do Tempo do Contrato</h5>
                </div>
                <div class="card-body">
                    <canvas id="timelineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Processo Vinculado -->
    @if($contrato->processo)
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Processo Vinculado</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Número do Processo:</strong></p>
                            <p>{{ $contrato->processo->numero_processo }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Data de Entrada:</strong></p>
                            <p>{{ \Carbon\Carbon::parse($contrato->processo->data_entrada)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Requisitante:</strong></p>
                            <p>{{ $contrato->processo->requisitante }}</p>
                        </div>
                        <div class="col-md-3">
                            <p class="mb-1"><strong>Modalidade:</strong></p>
                            <p>{{ $contrato->processo->modalidade }}</p>
                        </div>
                    </div>

                    @if($contrato->processo->categorias)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6>Valores por Categoria</h6>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Categoria</th>
                                            <th>Valor</th>
                                            <th>PA</th>
                                            <th>ND</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Consumo</td>
                                            <td>R$ {{ number_format($contrato->processo->categorias->valor_consumo ?? 0, 2, ',', '.') }}</td>
                                            <td>{{ $contrato->processo->categorias->detalhesDespesa->pa_consumo ?? '-' }}</td>
                                            <td>{{ $contrato->processo->categorias->detalhesDespesa->nd_consumo ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Permanente</td>
                                            <td>R$ {{ number_format($contrato->processo->categorias->valor_permanente ?? 0, 2, ',', '.') }}</td>
                                            <td>{{ $contrato->processo->categorias->detalhesDespesa->pa_permanente ?? '-' }}</td>
                                            <td>{{ $contrato->processo->categorias->detalhesDespesa->nd_permanente ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Serviço</td>
                                            <td>R$ {{ number_format($contrato->processo->categorias->valor_servico ?? 0, 2, ',', '.') }}</td>
                                            <td>{{ $contrato->processo->categorias->detalhesDespesa->pa_servico ?? '-' }}</td>
                                            <td>{{ $contrato->processo->categorias->detalhesDespesa->nd_servico ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Progresso da Vigência
    const progressoCtx = document.getElementById('progressoChart').getContext('2d');
    new Chart(progressoCtx, {
        type: 'doughnut',
        data: {
            labels: ['Dias Decorridos', 'Dias Restantes'],
            datasets: [{
                data: [
                    {{ $dadosVigencia['dias_passados'] }},
                    {{ $dadosVigencia['dias_restantes'] >= 0 ? $dadosVigencia['dias_restantes'] : 0 }}
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: `{{ $dadosVigencia['percentual_executado'] }}% do período decorrido`
                }
            }
        }
    });

    // Timeline do Contrato
    const timelineCtx = document.getElementById('timelineChart').getContext('2d');
    new Chart(timelineCtx, {
        type: 'line',
        data: {
            labels: ['Início', 'Hoje', 'Fim'],
            datasets: [{
                label: 'Linha do Tempo',
                data: [0, {{ $dadosVigencia['dias_passados'] }}, {{ $dadosVigencia['total_dias'] }}],
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Dias'
                    }
                }
            }
        }
    });
});
</script>
@endsection
