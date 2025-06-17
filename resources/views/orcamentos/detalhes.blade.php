@extends('layouts.app')

@section('title', 'Detalhes do Orçamento')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Detalhes do Orçamento</h1>
                <div>
                    <a href="{{ route('orcamentos.alocar', $orcamento->id) }}" class="btn btn-success me-2">
                        <i class="bi bi-plus-circle"></i> Alocar Orçamento
                    </a>
                    <a href="{{ route('orcamentos.orcamentos') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações Principais -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Informações Gerais</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Número:</dt>
                        <dd class="col-sm-8">{{ $orcamento->numero_orcamento }}</dd>

                        <dt class="col-sm-4">Fonte:</dt>
                        <dd class="col-sm-8">
                            <span class="badge {{ 
                                $orcamento->fonte->tipo === 'Federal' ? 'bg-primary' :
                                ($orcamento->fonte->tipo === 'Estadual' ? 'bg-success' :
                                ($orcamento->fonte->tipo === 'Municipal' ? 'bg-info' :
                                ($orcamento->fonte->tipo === 'Emenda Parlamentar' ? 'bg-warning' : 'bg-secondary')))
                            }}">
                                {{ $orcamento->fonte->nome }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Status:</dt>
                        <dd class="col-sm-8">
                            <span class="badge {{ 
                                $orcamento->status === 'Em Análise' ? 'bg-warning' :
                                ($orcamento->status === 'Aprovado' ? 'bg-success' :
                                ($orcamento->status === 'Reprovado' ? 'bg-danger' :
                                ($orcamento->status === 'Em Execução' ? 'bg-info' :
                                ($orcamento->status === 'Concluído' ? 'bg-primary' : 'bg-secondary'))))
                            }}">
                                {{ $orcamento->status }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Exercício:</dt>
                        <dd class="col-sm-8">{{ $orcamento->ano_exercicio }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">Valores</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Total:</dt>
                        <dd class="col-sm-8">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</dd>

                        <dt class="col-sm-4">Utilizado:</dt>
                        <dd class="col-sm-8">R$ {{ number_format($orcamento->valor_utilizado, 2, ',', '.') }}</dd>

                        <dt class="col-sm-4">Disponível:</dt>
                        <dd class="col-sm-8">R$ {{ number_format($orcamento->valor_disponivel, 2, ',', '.') }}</dd>

                        <dt class="col-sm-4">Progresso:</dt>
                        <dd class="col-sm-8">
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ 
                                    $orcamento->percentual_utilizado > 90 ? 'bg-danger' :
                                    ($orcamento->percentual_utilizado > 70 ? 'bg-warning' : 'bg-success')
                                }}" 
                                    role="progressbar" 
                                    style="width: {{ $orcamento->percentual_utilizado }}%"
                                    aria-valuenow="{{ $orcamento->percentual_utilizado }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                    {{ number_format($orcamento->percentual_utilizado, 1) }}%
                                </div>
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Períodos</h5>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">Início:</dt>
                        <dd class="col-sm-8">{{ $orcamento->data_inicio ? \Carbon\Carbon::parse($orcamento->data_inicio)->format('d/m/Y') : '-' }}</dd>

                        <dt class="col-sm-4">Término:</dt>
                        <dd class="col-sm-8">{{ $orcamento->data_fim ? \Carbon\Carbon::parse($orcamento->data_fim)->format('d/m/Y') : '-' }}</dd>

                        <dt class="col-sm-4">Dias Rest.:</dt>
                        <dd class="col-sm-8">
                            @if($orcamento->data_fim)
                                @php
                                    $diasRestantes = now()->diffInDays($orcamento->data_fim, false);
                                @endphp
                                <span class="badge {{ 
                                    $diasRestantes <= 7 ? 'bg-danger' :
                                    ($diasRestantes <= 30 ? 'bg-warning' : 'bg-success')
                                }}">
                                    {{ $diasRestantes }} dias
                                </span>
                            @else
                                -
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalhes Adicionais -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detalhes Adicionais</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Descrição</h6>
                            <p class="mb-4">{{ $orcamento->descricao ?: 'Nenhuma descrição fornecida.' }}</p>

                            <h6>Observações</h6>
                            <p class="mb-4">{{ $orcamento->observacoes ?: 'Nenhuma observação registrada.' }}</p>
                        </div>
                        <div class="col-md-6">
                            @if($orcamento->parlamentar || $orcamento->partido)
                                <h6>Informações da Emenda Parlamentar</h6>
                                <dl class="row">
                                    <dt class="col-sm-3">Parlamentar:</dt>
                                    <dd class="col-sm-9">{{ $orcamento->parlamentar ?: '-' }}</dd>

                                    <dt class="col-sm-3">Partido:</dt>
                                    <dd class="col-sm-9">{{ $orcamento->partido ?: '-' }}</dd>
                                </dl>
                            @endif

                            @if($orcamento->numero_convenio)
                                <h6>Convênio</h6>
                                <dl class="row">
                                    <dt class="col-sm-3">Número:</dt>
                                    <dd class="col-sm-9">{{ $orcamento->numero_convenio }}</dd>
                                </dl>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alocações -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Alocações e Utilizações</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if($orcamento->detalhesDespesa->isEmpty())
                        <div class="alert alert-info">
                            Nenhuma alocação registrada para este orçamento.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Processo</th>
                                        <th>Categoria</th>
                                        <th>Valor Alocado</th>
                                        <th>Utilizado</th>
                                        <th>Disponível</th>
                                        <th>Status</th>
                                        <th>Nº Empenho</th>
                                        <th>Data Empenho</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orcamento->detalhesDespesa as $detalhe)
                                    <tr>
                                        <td>{{ $detalhe->processo->numero_processo }}</td>
                                        <td>{{ $detalhe->categoria->nome }}</td>
                                        <td>R$ {{ number_format($detalhe->pivot->valor_alocado, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format($detalhe->pivot->valor_utilizado, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format($detalhe->pivot->valor_alocado - $detalhe->pivot->valor_utilizado, 2, ',', '.') }}</td>
                                        <td>
                                            <span class="badge {{ 
                                                $detalhe->pivot->status === 'Planejado' ? 'bg-warning' :
                                                ($detalhe->pivot->status === 'Empenhado' ? 'bg-info' :
                                                ($detalhe->pivot->status === 'Liquidado' ? 'bg-primary' :
                                                ($detalhe->pivot->status === 'Pago' ? 'bg-success' : 'bg-danger')))
                                            }}">
                                                {{ $detalhe->pivot->status }}
                                            </span>
                                        </td>
                                        <td>{{ $detalhe->pivot->numero_nota_empenho ?: '-' }}</td>
                                        <td>{{ $detalhe->pivot->data_empenho ? \Carbon\Carbon::parse($detalhe->pivot->data_empenho)->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            @if($detalhe->pivot->valor_alocado > $detalhe->pivot->valor_utilizado)
                                                <a href="{{ route('orcamentos.utilizar', ['id' => $orcamento->id, 'detalheId' => $detalhe->id]) }}" 
                                                    class="btn btn-sm btn-success">
                                                    <i class="bi bi-cash"></i> Utilizar
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
