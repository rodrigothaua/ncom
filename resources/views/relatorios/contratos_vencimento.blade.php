@extends('layouts.app')

@section('title', 'SIGECOM - Relatório de Contratos por Vencimento')

@section('content')
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Contratos por Vencimento</h5>
                    <div class="btn-group">
                        <a href="{{ route('relatorios.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Voltar
                        </a>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="bi bi-printer"></i> Imprimir
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @include('relatorios.partials.filtros')
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Número do Processo</th>
                                    <th>Empresa</th>
                                    <th>Valor do Contrato</th>
                                    <th>Data Inicial</th>
                                    <th>Data Final</th>
                                    <th>Status</th>
                                    <th class="no-print">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contratos as $contrato)
                                    <tr>
                                        <td>{{ $contrato->processo->numero_processo }}</td>
                                        <td>{{ $contrato->nome_empresa_contrato }}</td>
                                        <td>R$ {{ number_format($contrato->valor_contrato, 2, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($contrato->data_inicial_contrato)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($contrato->data_final_contrato)->format('d/m/Y') }}</td>
                                        <td>
                                            @php
                                                $dataFinal = \Carbon\Carbon::parse($contrato->data_final_contrato);
                                                $hoje = now();
                                                $diff = $dataFinal->diff($hoje);
                                                
                                                if ($dataFinal->isPast()) {
                                                    echo '<span class="badge bg-danger">Vencido</span>';
                                                } else {
                                                    $partes = [];
                                                    if ($diff->y > 0) {
                                                        $partes[] = $diff->y . ' ' . ($diff->y == 1 ? 'ano' : 'anos');
                                                    }
                                                    if ($diff->m > 0) {
                                                        $partes[] = $diff->m . ' ' . ($diff->m == 1 ? 'mês' : 'meses');
                                                    }
                                                    if ($diff->d > 0) {
                                                        $partes[] = $diff->d . ' ' . ($diff->d == 1 ? 'dia' : 'dias');
                                                    }
                                                    
                                                    $status = 'Falta' . (count($partes) > 1 ? 'm' : '') . ' ' . implode(', ', $partes);
                                                    
                                                    $diasTotais = $diff->days;
                                                    $class = 'bg-primary';
                                                    if ($diasTotais <= 30) {
                                                        $class = 'bg-danger';
                                                    } elseif ($diasTotais <= 60) {
                                                        $class = 'bg-warning';
                                                    } elseif ($diasTotais <= 90) {
                                                        $class = 'bg-warning';
                                                    } elseif ($diasTotais <= 180) {
                                                        $class = 'bg-success';
                                                    }
                                                    
                                                    echo '<span class="badge ' . $class . '">' . $status . '</span>';
                                                }
                                            @endphp
                                        </td>
                                        <td class="no-print">
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detalhesModal{{ $contrato->id }}">
                                                <i class="bi bi-eye"></i> Detalhes
                                            </button>
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
</div>

@foreach($contratos as $contrato)
    <!-- Modal de Detalhes -->
    <div class="modal fade" id="detalhesModal{{ $contrato->id }}" tabindex="-1" aria-labelledby="detalhesModalLabel{{ $contrato->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalhesModalLabel{{ $contrato->id }}">Detalhes do Contrato</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Empresa:</strong> {{ $contrato->nome_empresa_contrato }}
                        </div>
                        <div class="col-md-6">
                            <strong>CNPJ:</strong> {{ $contrato->cnpj_contrato }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Telefone:</strong> {{ $contrato->numero_telefone_contrato }}
                        </div>
                        <div class="col-md-6">
                            <strong>Valor do Contrato:</strong> R$ {{ number_format($contrato->valor_contrato, 2, ',', '.') }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Observações:</strong>
                            <p>{{ $contrato->observacoes ?? 'Nenhuma observação registrada.' }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Valores por Categoria:</strong>
                            <ul class="list-unstyled">
                                <li>Consumo: R$ {{ number_format($contrato->processo->categorias->valor_consumo ?? 0, 2, ',', '.') }}</li>
                                <li>Permanente: R$ {{ number_format($contrato->processo->categorias->valor_permanente ?? 0, 2, ',', '.') }}</li>
                                <li>Serviço: R$ {{ number_format($contrato->processo->categorias->valor_servico ?? 0, 2, ',', '.') }}</li>
                            </ul>
                        </div>
                    </div>
                    @if($contrato->processo->categorias && $contrato->processo->categorias->detalhesDespesa)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Números PA/ND:</strong>
                                <ul class="list-unstyled">
                                    @php $detalhes = $contrato->processo->categorias->detalhesDespesa; @endphp
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
        .badge {
            border: 1px solid #000 !important;
        }
    }
    .table td {
        vertical-align: middle;
        word-wrap: break-word;
        max-width: 300px;
        padding: 15px 10px;
    }
    .badge {
        white-space: normal !important;
        text-align: left;
        padding: 8px 12px;
        line-height: 1.3;
    }
</style>
@endsection
