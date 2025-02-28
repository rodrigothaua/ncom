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


    <table class="table">
        <thead>
            <tr>
                <th>Número do Processo</th>
                <th>Descrição</th>
                <th>Requisitante</th>
                <th>Data de Entrada</th>
                <th>Valor Total</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($processos as $processo)
                <tr>
                    <td>{{ $processo->numero_processo }}</td>
                    <td>{{ $processo->descricao }}</td>
                    <td>{{ $processo->requisitante }}</td>
                    <td>{{ $processo->data_entrada ? date('d/m/Y', strtotime($processo->data_entrada)) : '-' }}</td>
                    <td class="valor-total" data-consumo="{{ $processo->categorias ? $processo->categorias->valor_consumo : 0 }}" data-permanente="{{ $processo->categorias ? $processo->categorias->valor_permanente : 0 }}" data-servico="{{ $processo->categorias ? $processo->categorias->valor_servico : 0 }}"></td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="mostrarDetalhes({{ json_encode($processo) }})">Detalhes</button>
                        <a href="{{ route('processos.edit', $processo->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('processos.destroy', $processo->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal de Detalhes -->
    <div class="modal fade" id="modalDetalhes" tabindex="-1" aria-labelledby="modalDetalhesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalhesLabel">Detalhes do Processo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Número do Processo:</strong> <span id="det-numero"></span></p>
                <p><strong>Descrição:</strong> <span id="det-descricao"></span></p>
                <p><strong>Requisitante:</strong> <span id="det-requisitante"></span></p>
                <p><strong>Data de Entrada:</strong> <span id="det-data-entrada"></span></p>
                <p><strong>Modalidade:</strong> <span id="det-modalidade"></span></p>
                <p><strong>Procedimentos Auxiliares:</strong> <span id="det-procedimentos"></span></p>

                <h5 class="mt-3">Categorias</h5>
                <p><strong>Valor Consumo:</strong> R$ <span id="det-valor-consumo"></span></p>
                <p><strong>Valor Permanente:</strong> R$ <span id="det-valor-permanente"></span></p>
                <p><strong>Valor Serviço:</strong> R$ <span id="det-valor-servico"></span></p>
                <p><strong>Valor Total:</strong> R$ <span id="det-valor-total"></span></p>

                <h5 class="mt-3">Detalhes das Despesas</h5>
                <p><strong>PA Consumo:</strong> <span id="det-pa-consumo"></span></p>
                <p><strong>Natureza Despesa Consumo:</strong> <span id="det-nd-consumo"></span></p>
                <p><strong>PA Permanente:</strong> <span id="det-pa-permanente"></span></p>
                <p><strong>Natureza Despesa Permanente:</strong> <span id="det-nd-permanente"></span></p>
                <p><strong>PA Serviço:</strong> <span id="det-pa-servico"></span></p>
                <p><strong>Natureza Despesa Serviço:</strong> <span id="det-nd-servico"></span></p>

                <h5 class="mt-3">Contratos</h5>
                <ul id="det-contratos" class="list-group"></ul>
            </div>
        </div>
    </div>
</div>

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
@endsection
