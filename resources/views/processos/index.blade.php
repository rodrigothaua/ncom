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
            <tr class="table-th">
                <th>Número do Processo</th>
                <th>Descrição</th>
                <th>Requisitante</th>
                <th>Data Entrada</th>
                <th>Valor Total</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($processos as $processo)
            <tr>
                <td>{{ $processo->numero_processo }}</td>
                <td>{{ $processo->descricao }}</td>
                <td>{{ $processo->requisitante }}</td>
                <td>{{ $processo->data_entrada ? date('d/m/Y', strtotime($processo->data_entrada)) : '-' }}</td>
                <td><strong>R$ {{ number_format($processo->valor_total, 2, ',', '.') }}</strong></td>
                <td>
                    <a href="{{ route('processos.edit', $processo->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('processos.destroy', $processo->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                    <button class="btn btn-sm btn-info" onclick="mostrarDetalhes({{ $processo }})">Detalhes</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Nenhum processo cadastrado.</td>
            </tr>
            @endforelse
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
                    <p><strong>Procedimentos:</strong> <span id="det-procedimentos"></span></p>
                    <p><strong>Valor Consumo:</strong> R$ <span id="det-valor-consumo"></span></p>
                    <p><strong>Valor Permanente:</strong> R$ <span id="det-valor-permanente"></span></p>
                    <p><strong>Valor Serviço:</strong> R$ <span id="det-valor-servico"></span></p>
                    <p><strong>Valor Total:</strong> R$ <span id="det-valor-total"></span></p>
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

<!-- Script para carregar os dados no modal -->
<script>
    function mostrarDetalhes(processo) {
        document.getElementById("det-numero").innerText = processo.numero_processo;
        document.getElementById("det-descricao").innerText = processo.descricao;
        document.getElementById("det-requisitante").innerText = processo.requisitante;
        document.getElementById("det-data-entrada").innerText = processo.data_entrada ? new Date(processo.data_entrada).toLocaleDateString("pt-BR") : '-';
        document.getElementById("det-modalidade").innerText = processo.modalidade;
        document.getElementById("det-procedimentos").innerText = processo.procedimentos;
        document.getElementById("det-valor-consumo").innerText = Number(processo.valor_consumo).toLocaleString("pt-BR", { minimumFractionDigits: 2 });
        document.getElementById("det-valor-permanente").innerText = Number(processo.valor_permanente).toLocaleString("pt-BR", { minimumFractionDigits: 2 });
        document.getElementById("det-valor-servico").innerText = Number(processo.valor_servico).toLocaleString("pt-BR", { minimumFractionDigits: 2 });
        document.getElementById("det-valor-total").innerText = Number(processo.valor_total).toLocaleString("pt-BR", { minimumFractionDigits: 2 });

        // Adicionando contratos ao modal
        let contratosLista = document.getElementById("det-contratos");
        contratosLista.innerHTML = ""; // Limpar lista antes de adicionar novos contratos
        if (processo.contratos.length > 0) {
            processo.contratos.forEach(contrato => {
                let li = document.createElement("li");
                li.className = "list-group-item";
                li.innerHTML = `<strong>Nº:</strong> ${contrato.numero_contrato} | <strong>Valor:</strong> R$ ${Number(contrato.valor_contrato).toLocaleString("pt-BR", { minimumFractionDigits: 2 })} | <strong>Data Inicial:</strong> ${new Date(contrato.data_inicial_contrato).toLocaleDateString("pt-BR")} | <strong>Data Final:</strong> ${new Date(contrato.data_final_contrato).toLocaleDateString("pt-BR")}`;
                contratosLista.appendChild(li);
            });
        } else {
            contratosLista.innerHTML = "<li class='list-group-item text-muted'>Nenhum contrato</li>";
        }

        var modal = new bootstrap.Modal(document.getElementById('modalDetalhes'));
        modal.show();
    }
</script>
@endsection
