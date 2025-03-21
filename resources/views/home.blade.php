@extends('layouts.app')

@section('title', 'SIGECOM - Visão Geral')

@section('content')
    @include('layouts.navbar')

    <main>
        <section id="home">
            <div class="container-fluid">
                <h5>RESUMO/CONTRATOS</h5>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card mb-3 bg-primary text-white">
                            <div class="row g-0">
                                <div class="col-md-3 icon">
                                    <i class="bi bi-file-earmark-medical"></i>
                                </div>
                                <div class="col-md-9">
                                    <div class="card-body">
                                        <h5 class="card-title">Total de Contratos: {{ $totalProcessos }}</h5>
                                        <hr>
                                        <p class="card-text"><small class="text-body-primary">100% Contratos ativos</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3 bg-danger text-white">
                            <div class="row g-0">
                                <div class="col-md-3 icon">
                                    <i class="bi bi-file-earmark"></i>
                                </div>
                                <div class="col-md-9">
                                    <div class="card-header">VENCEM(-30 DIAS)</div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $vencimentos['totalMenos30Dias'] }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3 text-white" style="background: #FF7701;">
                            <div class="row g-0">
                                <div class="col-md-3 icon">
                                    <i class="bi bi-file-earmark"></i>
                                </div>
                                <div class="col-md-9">
                                    <div class="card-header">VENCEM(30 A 60 DIAS)</div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $vencimentos['total30a60Dias'] }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3 bg-warning text-white">
                            <div class="row g-0">
                                <div class="col-md-3 icon">
                                    <i class="bi bi-file-earmark"></i>
                                </div>
                                <div class="col-md-9">
                                    <div class="card-header">VENCEM(60 A 90 DIAS)</div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $vencimentos['total60a90Dias'] }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3 bg-success text-white">
                            <div class="row g-0">
                                <div class="col-md-3 icon">
                                    <i class="bi bi-file-earmark"></i>
                                </div>
                                <div class="col-md-9">
                                    <div class="card-header">VENCEM(90 A 180 DIAS)</div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $vencimentos['total90a180Dias'] }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3 bg-primary text-white">
                            <div class="row g-0">
                                <div class="col-md-3 icon">
                                    <i class="bi bi-file-earmark"></i>
                                </div>
                                <div class="col-md-9">
                                    <div class="card-header">VENCEM(+ 180 DIAS)</div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $vencimentos['totalMais180Dias'] }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header"><b><i class="bi bi-currency-dollar"></i> TOTAL GLOBAL</b></div>
                            <div class="card-body text-center">
                                <h2><b>R$ {{ number_format($valorTotal, 2, ',', '.') }}</b></h2>
                            </div>
                        </div>
                        <br>
                        <div class="card">
                            <div class="card-header">
                                <b><i class="bi bi-pie-chart-fill"></i> Por Categorias</b>
                            </div>

                            <div class="card-body">
                                <canvas id="categoriasChart"
                                    data-consumo="{{ $totais['valorConsumo'] }}"
                                    data-permanente="{{ $totais['valorPermanente'] }}"
                                    data-servico="{{ $totais['valorServico'] }}"
                                    style="height: 480px;">
                                </canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header"><b><i class="bi bi-pie-chart-fill"></i> Por Ano</b></div>
                            <div class="card-body">
                                <canvas id="contratosPorAnoExemplo"
                                    data-anos="{{ json_encode(array_column($contratosPorAno->toArray(), 'ano')) }}"
                                    data-totais="{{ json_encode(array_column($contratosPorAno->toArray(), 'total')) }}"
                                    style="height: 645px;">
                                </canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header"><b><i class="bi bi-pie-chart-fill"></i> Por Mês</b></div>
                            <div class="card-body">
                                <canvas id="evolucaoCategoriasChart"
                                    data-meses="{{ json_encode(array_keys($dadosCategoriasPorMes)) }}"
                                    data-consumo="{{ json_encode(array_column($dadosCategoriasPorMes, 'consumo')) }}"
                                    data-permanente="{{ json_encode(array_column($dadosCategoriasPorMes, 'permanente')) }}"
                                    data-servico="{{ json_encode(array_column($dadosCategoriasPorMes, 'servico')) }}">
                                </canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <b><i class="bi bi-table"></i> Lista de Processos e Contratos</b>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <form id="search-form" class="row" method="GET" action="{{ url('/') }}">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                <input type="text" name="search" class="form-control" placeholder="Pesquisar por processo, requisitante, descrição..." value="{{ request('search') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <select name="filtro_vencimento" class="form-select" onchange="this.form.submit()">
                                            <option value="todos" {{ request('filtro_vencimento') == 'todos' ? 'selected' : '' }}>Todos os Contratos</option>
                                            <option value="menos30" {{ request('filtro_vencimento') == 'menos30' ? 'selected' : '' }}>Vencendo em -30 dias</option>
                                            <option value="30a60" {{ request('filtro_vencimento') == '30a60' ? 'selected' : '' }}>Vencendo entre 30 e 60 dias</option>
                                            <option value="60a90" {{ request('filtro_vencimento') == '60a90' ? 'selected' : '' }}>Vencendo entre 60 e 90 dias</option>
                                            <option value="90a180" {{ request('filtro_vencimento') == '90a180' ? 'selected' : '' }}>Vencendo entre 90 e 180 dias</option>
                                            <option value="mais180" {{ request('filtro_vencimento') == 'mais180' ? 'selected' : '' }}>Vencendo em +180 dias</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <style>
                                        .table td, .table th {
                                            vertical-align: middle;
                                            word-wrap: break-word;
                                            max-width: 300px;
                                            padding: 15px 10px;
                                        }
                                        .table tr {
                                            border-bottom: 2px solid #dee2e6;
                                        }
                                        .table tbody tr:hover {
                                            background-color: rgba(0,0,0,.075);
                                        }
                                        .status-badge {
                                            white-space: normal !important;
                                            display: inline-block;
                                            line-height: 1.3;
                                            padding: 8px 12px;
                                            text-align: center;
                                            width: 100%;
                                        }
                                    </style>
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 150px">Número do Processo</th>
                                                <th style="min-width: 150px">Requisitante</th>
                                                <th style="min-width: 200px">Descrição</th>
                                                <th style="min-width: 120px">Data Inicial</th>
                                                <th style="min-width: 120px">Data Final</th>
                                                <th style="min-width: 200px">Status</th>
                                                <th style="min-width: 100px">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($contratos as $contrato)
                                                <tr>
                                                    <td style="white-space: normal">{{ $contrato->processo->numero_processo }}</td>
                                                    <td style="white-space: normal">{{ $contrato->processo->requisitante }}</td>
                                                    <td style="white-space: normal">{{ $contrato->processo->descricao }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($contrato->data_inicial_contrato)->format('d/m/Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($contrato->data_final_contrato)->format('d/m/Y') }}</td>
                                                    <td>
                                                        @php
                                                            $dataFinal = \Carbon\Carbon::parse($contrato->data_final_contrato);
                                                            $hoje = now();
                                                            $diff = $dataFinal->diff($hoje);
                                                            
                                                            $status = '';
                                                            $class = '';
                                                            
                                                            if ($dataFinal->isPast()) {
                                                                $status = 'Vencido';
                                                                $class = 'bg-danger';
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
                                                                
                                                                $status = 'Falta' . (count($partes) > 1 ? 'm' : '') . ' ' . implode(', ', $partes) . ' para o vencimento';
                                                                
                                                                $diasTotais = $diff->days;
                                                                if ($diasTotais <= 30) {
                                                                    $class = 'bg-danger';
                                                                } elseif ($diasTotais <= 60) {
                                                                    $class = 'bg-warning';
                                                                } elseif ($diasTotais <= 90) {
                                                                    $class = 'bg-warning';
                                                                } elseif ($diasTotais <= 180) {
                                                                    $class = 'bg-success';
                                                                } else {
                                                                    $class = 'bg-primary';
                                                                }
                                                            }
                                                        @endphp
                                        <span class="badge {{ $class }} status-badge">{{ $status }}</span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detalhesModal{{ $contrato->id }}">
                                                            <i class="bi bi-eye"></i> Detalhes
                                                        </button>
                                                    </td>
                                                </tr>
                                                
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
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
