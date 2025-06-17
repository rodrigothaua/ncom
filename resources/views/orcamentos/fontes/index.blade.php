@extends('layouts.app')

@section('title', 'Fontes de Orçamento')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Fontes de Orçamento</h1>
                <a href="{{ route('orcamentos.fontes.criar') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nova Fonte
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($fontes->isEmpty())
                        <div class="alert alert-info">
                            Nenhuma fonte de orçamento cadastrada.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Código</th>
                                        <th>Tipo</th>
                                        <th>Status</th>
                                        <th>Orçamentos</th>
                                        <th>Valor Total</th>
                                        <th>Utilizado</th>
                                        <th>Disponível</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fontes as $fonte)
                                    <tr>
                                        <td>{{ $fonte->nome }}</td>
                                        <td><code>{{ $fonte->codigo }}</code></td>
                                        <td>
                                            <span class="badge {{ 
                                                $fonte->tipo === 'Federal' ? 'bg-primary' :
                                                ($fonte->tipo === 'Estadual' ? 'bg-success' :
                                                ($fonte->tipo === 'Municipal' ? 'bg-info' :
                                                ($fonte->tipo === 'Emenda Parlamentar' ? 'bg-warning' : 'bg-secondary')))
                                            }}">
                                                {{ $fonte->tipo }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($fonte->ativo)
                                                <span class="badge bg-success">Ativa</span>
                                            @else
                                                <span class="badge bg-danger">Inativa</span>
                                            @endif
                                        </td>
                                        <td>{{ $fonte->orcamentos->count() }}</td>
                                        <td>R$ {{ number_format($fonte->valor_total, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format($fonte->valor_utilizado, 2, ',', '.') }}</td>
                                        <td>R$ {{ number_format($fonte->valor_disponivel, 2, ',', '.') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Ações
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('orcamentos.orcamentos', ['fonte_id' => $fonte->id]) }}">
                                                            <i class="bi bi-list-ul"></i> Ver Orçamentos
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editarFonte{{ $fonte->id }}">
                                                            <i class="bi bi-pencil"></i> Editar
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('orcamentos.fontes.toggle', $fonte->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="dropdown-item text-{{ $fonte->ativo ? 'danger' : 'success' }}">
                                                                <i class="bi bi-toggle-{{ $fonte->ativo ? 'off' : 'on' }}"></i>
                                                                {{ $fonte->ativo ? 'Desativar' : 'Ativar' }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- Modal de Edição -->
                                            <div class="modal fade" id="editarFonte{{ $fonte->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('orcamentos.fontes.atualizar', $fonte->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Editar Fonte de Orçamento</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nome</label>
                                                                    <input type="text" name="nome" class="form-control" value="{{ $fonte->nome }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Código</label>
                                                                    <input type="text" name="codigo" class="form-control" value="{{ $fonte->codigo }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tipo</label>
                                                                    <select name="tipo" class="form-select" required>
                                                                        <option value="Federal" {{ $fonte->tipo === 'Federal' ? 'selected' : '' }}>Federal</option>
                                                                        <option value="Estadual" {{ $fonte->tipo === 'Estadual' ? 'selected' : '' }}>Estadual</option>
                                                                        <option value="Municipal" {{ $fonte->tipo === 'Municipal' ? 'selected' : '' }}>Municipal</option>
                                                                        <option value="Emenda Parlamentar" {{ $fonte->tipo === 'Emenda Parlamentar' ? 'selected' : '' }}>Emenda Parlamentar</option>
                                                                        <option value="Outro" {{ $fonte->tipo === 'Outro' ? 'selected' : '' }}>Outro</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Descrição</label>
                                                                    <textarea name="descricao" class="form-control" rows="3">{{ $fonte->descricao }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-primary">Salvar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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
