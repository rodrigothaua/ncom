@extends('layouts.app')

@if (request()->is('processos*')) 
    @include('layouts.navbar')
@endif

@section('content')
@include('layouts.partials.alerts')
<div class="container-fluid">
    <h1 class="mb-4">Gerenciamento de Processos</h1>

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card text-white bg-primary mb-4">
                <div class="card-body">
                    <h5 class="card-title">Total de Processos</h5>
                    <!--<canvas id="processosPieChart"></canvas>-->
                    <p class="card-text">Visualize todo o histórico de processos.</p>
                    <a href="{{ route('processos.index') }}" class="btn btn-light">Ver todos os processos</a>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card text-white bg-success mb-4">
                <div class="card-body">
                    <h5 class="card-title">Novo Processo</h5>
                    <p class="card-text">Cadastre um novo processo para começar a acompanhar.</p>
                    <a href="{{ route('processos.create') }}" class="btn btn-light">Adicionar Processo</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalNovoProcesso">Novo Processo</a>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Número do Processo</th>
                <th>Descrição</th>
                <th>Requisitante</th>
                <th>Categorias</th>
                <th>Valor Total</th>
                <th>Data Início</th>
                <th>Data Vencimento</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($processos as $processo)
            <tr>
                <td>{{ $processo->id }}</td>
                <td>{{ $processo->numero_processo }}</td>
                <td>{{ $processo->descricao }}</td>
                <td>{{ $processo->requisitante }}</td>
                <td>
                    @php
                        $categorias = is_string($processo->categoria) ? json_decode($processo->categoria, true) : $processo->categoria;
                    @endphp

                    @if (!empty($categorias) && is_array($categorias))
                        {{ implode(', ', $categorias) }}
                    @else
                        Nenhuma
                    @endif
                </td>
                <td>{{ $processo->valor_total ? 'R$ ' . number_format($processo->valor_total, 2, ',', '.') : '-' }}</td>
                <td>{{ $processo->data_inicio ? date('d/m/Y', strtotime($processo->data_inicio)) : '-' }}</td>
                <td>{{ $processo->data_vencimento ? date('d/m/Y', strtotime($processo->data_vencimento)) : '-' }}</td>
                <td>
                    <a href="{{ route('processos.edit', $processo->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('processos.destroy', $processo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Nenhum processo cadastrado.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Paginação -->
    <nav>
        <ul class="pagination justify-content-center">
            {{-- Link para a página anterior --}}
            @if ($processos->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Anterior</span></li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $processos->previousPageUrl() }}" rel="prev">Anterior</a>
                </li>
            @endif

            {{-- Links das páginas --}}
            @foreach ($processos->getUrlRange(1, $processos->lastPage()) as $page => $url)
                <li class="page-item {{ $page == $processos->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Link para a próxima página --}}
            @if ($processos->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $processos->nextPageUrl() }}" rel="next">Próximo</a>
                </li>
            @else
                <li class="page-item disabled"><span class="page-link">Próximo</span></li>
            @endif
        </ul>
    </nav>
</div>
<br>


<!-- Modal para Novo Processo -->
<div class="modal fade" id="modalNovoProcesso" tabindex="-1" aria-labelledby="modalNovoProcessoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Cadastrar novo processo
                </h1>
            </div>
            <div class="modal-body">
                <form action="{{ route('processos.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="numero_processo">Número do Processo</label>
                        <input type="text" id="numero_processo" name="numero_processo" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea id="descricao" name="descricao" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Requisitante</label>
                        <input type="text" id="requisitante" name="requisitante" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="categoria" class="form-label">Categoria</label>
                        <select class="form-select" name="categoria" id="categoria" require>
                            <option selected>Selecione a categoria</option>
                            <option value="consumo">Consumo</option>
                            <option value="permanente">Permanente</option>
                            <option value="serviço">Serviço</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="valor_contrato" class="form-label">Valor do contrato</label>
                        <input type="number" step="0.01" class="form-control" id="valor_total" name="valor_total" placeholder="Digite o valor do contrato" required>
                    </div>
                    <div class="form-group">
                        <label for="data_inicio">Data de Início</label>
                        <input type="date" id="data_inicio" name="data_inicio" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="data_vencimento">Data de Vencimento</label>
                        <input type="date" id="data_vencimento" name="data_vencimento" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Cadastrar Processo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="descricaoModal" tabindex="-1" aria-labelledby="descricaoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descricaoModalLabel">Descrição Completa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="descricaoCompleta"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function carregarDescricao(descricao) {
        // Atualiza o conteúdo do modal com a descrição completa
        document.getElementById('descricaoCompleta').textContent = descricao;
    }
</script>
@endsection
