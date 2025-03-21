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
@endsection
