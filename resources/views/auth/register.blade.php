@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Perfil do Usuário</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="https://via.placeholder.com/150" alt="Foto de Perfil" class="img-fluid rounded-circle">
                </div>
                <div class="col-md-8">
                    <h4 class="mb-3">Informações Pessoais</h4>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Nome:</strong> {{ $user->name }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                        <li class="list-group-item"><strong>Telefone:</strong> {{ $user->telefone }}</li>
                        <li class="list-group-item"><strong>Data de Registro:</strong> {{ $user->created_at->format('d/m/Y') }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('editar.perfil') }}" class="btn btn-warning">Editar Perfil</a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Voltar</a>
        </div>
    </div>
</div>
@endsection
