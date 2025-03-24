@extends('layouts.app')

@section('title', 'SIGECOM - Relatórios')

@section('content')
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-12">
            <h2 class="h3 mb-4">Relatórios</h2>
        </div>
    </div>

    <div class="row">
        <!-- Filtro Geral -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header" data-bs-toggle="collapse" href="#filtroGeralContent" role="button">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-funnel"></i> Filtro Geral
                        </h5>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                </div>
                <div class="collapse show" id="filtroGeralContent">
                    <div class="card-body">
                        <p class="card-text">Pesquisa avançada com todos os filtros disponíveis</p>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Por Processo</h6>
                                        <p class="small">Filtrar por número, requisitante e datas</p>
                                        <a href="{{ route('relatorios.filtro.geral', ['tipo' => 'processo']) }}" class="btn btn-sm btn-outline-primary">
                                            Acessar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Por Categoria</h6>
                                        <p class="small">Filtrar por valores de consumo, permanente e serviço</p>
                                        <a href="{{ route('relatorios.filtro.geral', ['tipo' => 'categoria']) }}" class="btn btn-sm btn-outline-primary">
                                            Acessar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Por PA/ND</h6>
                                        <p class="small">Filtrar por números PA e natureza de despesa</p>
                                        <a href="{{ route('relatorios.filtro.geral', ['tipo' => 'despesa']) }}" class="btn btn-sm btn-outline-primary">
                                            Acessar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-subtitle mb-2">Por Contrato</h6>
                                        <p class="small">Filtrar por informações contratuais</p>
                                        <a href="{{ route('relatorios.filtro.geral', ['tipo' => 'contrato']) }}" class="btn btn-sm btn-outline-primary">
                                            Acessar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Relatórios Específicos -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-calendar-check"></i> Contratos por Vencimento
                    </h5>
                    <p class="card-text">Visualize todos os contratos ordenados por data de vencimento.</p>
                    <a href="{{ route('relatorios.contratos.vencimento') }}" class="btn btn-primary">
                        Acessar Relatório
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-cash"></i> Contratos por Valor
                    </h5>
                    <p class="card-text">Visualize todos os contratos ordenados por valor.</p>
                    <a href="{{ route('relatorios.contratos.valor') }}" class="btn btn-primary">
                        Acessar Relatório
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-pie-chart"></i> Categorias por Processo
                    </h5>
                    <p class="card-text">Análise detalhada das categorias por processo.</p>
                    <a href="{{ route('relatorios.categorias.processo') }}" class="btn btn-primary">
                        Acessar Relatório
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle dos ícones de chevron
    const collapseElement = document.getElementById('filtroGeralContent');
    const chevronIcon = document.querySelector('.bi-chevron-down');

    collapseElement.addEventListener('show.bs.collapse', function () {
        chevronIcon.classList.remove('bi-chevron-down');
        chevronIcon.classList.add('bi-chevron-up');
    });

    collapseElement.addEventListener('hide.bs.collapse', function () {
        chevronIcon.classList.remove('bi-chevron-up');
        chevronIcon.classList.add('bi-chevron-down');
    });
});
</script>
@endsection
