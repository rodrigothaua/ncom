@extends('layouts.app')

@section('title', 'Lista de Orçamentos')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Orçamentos</h1>
                <a href="{{ route('orcamentos.criar') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Novo Orçamento
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('orcamentos.orcamentos') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="fonte_id" class="form-label">Fonte</label>
                                    <select name="fonte_id" id="fonte_id" class="form-select">
                                        <option value="">Todas as fontes</option>
                                        @foreach($fontes as $fonte)
                                            <option value="{{ $fonte->id }}" {{ request('fonte_id') == $fonte->id ? 'selected' : '' }}>
                                                {{ $fonte->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="ano" class="form-label">Ano</label>
                                    <select name="ano" id="ano" class="form-select">
                                        <option value="">Todos os anos</option>
                                        @for ($i = date('Y') - 5; $i <= date('Y') + 5; $i++)
                                            <option value="{{ $i }}" {{ request('ano') == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Todos os status</option>
                                        <option value="Em Análise" {{ request('status') == 'Em Análise' ? 'selected' : '' }}>Em Análise</option>
                                        <option value="Aprovado" {{ request('status') == 'Aprovado' ? 'selected' : '' }}>Aprovado</option>
                                        <option value="Reprovado" {{ request('status') == 'Reprovado' ? 'selected' : '' }}>Reprovado</option>
                                        <option value="Em Execução" {{ request('status') == 'Em Execução' ? 'selected' : '' }}>Em Execução</option>
                                        <option value="Concluído" {{ request('status') == 'Concluído' ? 'selected' : '' }}>Concluído</option>
                                        <option value="Cancelado" {{ request('status') == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="mb-3 w-100">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-search"></i> Filtrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Orçamentos -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($orcamentos->isEmpty())
                        <div class="alert alert-info">
                            Nenhum orçamento encontrado com os filtros selecionados.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Número</th>
                                        <th>Fonte</th>
                                        <th>Valor Total</th>
                                        <th>Utilizado</th>
                                        <th>Disponível</th>
                                        <th>Status</th>
                                        <th>Início</th>
                                        <th>Fim</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orcamentos as $orcamento)
                                    <tr>
                                        <td>
                                            <a href="{{ route('orcamentos.detalhes', $orcamento->id) }}" class="text-decoration-none">
                                                {{ $orcamento->numero_orcamento }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge {{ 
                                                $orcamento->fonte->tipo === 'Federal' ? 'bg-primary' :
                                                ($orcamento->fonte->tipo === 'Estadual' ? 'bg-success' :
                                                ($orcamento->fonte->tipo === 'Municipal' ? 'bg-info' :
                                                ($orcamento->fonte->tipo === 'Emenda Parlamentar' ? 'bg-warning' : 'bg-secondary')))
                                            }}">
                                                {{ $orcamento->fonte->nome }}
                                            </span>
                                        </td>
                                        <td>R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format($orcamento->valor_utilizado, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format($orcamento->valor_disponivel, 2, ',', '.') }}</td>
                                        <td>
                                            <span class="badge {{ 
                                                $orcamento->status === 'Em Análise' ? 'bg-warning' :
                                                ($orcamento->status === 'Aprovado' ? 'bg-success' :
                                                ($orcamento->status === 'Reprovado' ? 'bg-danger' :
                                                ($orcamento->status === 'Em Execução' ? 'bg-info' :
                                                ($orcamento->status === 'Concluído' ? 'bg-primary' : 'bg-secondary'))))
                                            }}">
                                                {{ $orcamento->status }}
                                            </span>
                                        </td>
                                        <td>{{ $orcamento->data_inicio ? \Carbon\Carbon::parse($orcamento->data_inicio)->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $orcamento->data_fim ? \Carbon\Carbon::parse($orcamento->data_fim)->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Ações
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('orcamentos.detalhes', $orcamento->id) }}">
                                                            <i class="bi bi-eye"></i> Ver Detalhes
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('orcamentos.alocar', $orcamento->id) }}">
                                                            <i class="bi bi-plus-circle"></i> Alocar Orçamento
                                                        </a>
                                                    </li>
                                                    @if($orcamento->status === 'Em Análise')
                                                    <li>
                                                        <form action="{{ route('orcamentos.aprovar', $orcamento->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dropdown-item text-success">
                                                                <i class="bi bi-check-circle"></i> Aprovar
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('orcamentos.reprovar', $orcamento->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="bi bi-x-circle"></i> Reprovar
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div class="d-flex justify-content-end mt-3">
                            {{ $orcamentos->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
