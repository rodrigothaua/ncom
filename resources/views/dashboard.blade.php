<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="my-4">Bem-vindo ao Dashboard</h1>
        
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card text-white bg-primary mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Total de Processos</h5>
                        <p class="card-text">Aqui você pode ver o total de processos cadastrados.</p>
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
    </div>
@endsection
