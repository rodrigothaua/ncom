@extends('layouts.app')

@if (request()->is('processos*')) 
    @include('layouts.navbar')
@endif

@section('content')
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

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabela de Processos -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><b><i class="bi bi-list-task"></i> LISTA DE PROCESSOS</b></div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Número do Processo</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">Categoria</th>
                                <th scope="col" style="width: 200px;">Valor Total</th>
                                <th scope="col">Data de Início</th>
                                <th scope="col">Data de Vencimento</th>
                                <!--<th scope="col">Status</th>-->
                                <th scope="col" style="width: 200px;">Funções</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                                @foreach ($processos as $processo)
                                <tr 
                                    @if (\Carbon\Carbon::parse($processo->data_vencimento)->isPast()) class="table-danger" 
                                    @elseif (\Carbon\Carbon::parse($processo->data_vencimento)->diffInMonths() <= 3) class="table-warning" 
                                    @elseif (\Carbon\Carbon::parse($processo->data_vencimento)->diffInMonths() <= 6) class="table-info" 
                                    @endif
                                >
                                    <th>{{ $processo->id }}</th>
                                    <td>{{ $processo->numero_processo }}</td>
                                    <td>
                                        {{ \Illuminate\Support\Str::limit($processo->descricao, 50) }}
                                        @if (strlen($processo->descricao) > 50)
                                            <button 
                                                class="btn btn-link p-0 text-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#descricaoModal" 
                                                onclick="carregarDescricao('{{ $processo->descricao }}')">
                                                Ver Completo
                                            </button>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($processo->categoria) }}</td>
                                    <td>R$ {{ number_format($processo->valor_total, 2, ',', '.') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($processo->data_inicio)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($processo->data_vencimento)->format('d/m/Y') }}</td>
                                    <!--
                                    <td>
                                        @if (\Carbon\Carbon::parse($processo->data_vencimento)->isPast())
                                            Vencido
                                        @elseif (\Carbon\Carbon::parse($processo->data_vencimento)->diffInMonths() <= 3)
                                            + 3 meses
                                        @elseif (\Carbon\Carbon::parse($processo->data_vencimento)->diffInMonths() <= 6)
                                            + 6 meses
                                        @else
                                            + 12 meses
                                        @endif
                                    </td>-->
                                    <td>
                                        <a href="{{ route('processos.edit', $processo->id) }}" class="btn btn-primary btn-sm">Editar</a>
                                        <form action="{{ route('processos.destroy', $processo->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este processo?')">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="legenda">
        <div class="badge" style="width: 6rem; background:rgb(253, 143, 152); color: #000;">
            Vencido
        </div>
        <div class="badge" style="width: 6rem; background: #FFF3CD; color: #000;">
            + 3 meses
        </div>
        <div class="badge" style="width: 6rem; background: #CFF4FC; color: #000;">
            + 6 meses
        </div>
        <div class="badge" style="width: 6rem; background: #FFF; color: #000;">
            + 12 meses
        </div>
    </div>
</div>

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
