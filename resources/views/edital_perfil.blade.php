@extends('layouts.app')

@section('content')
<div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Editar Perfil</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('perfil.atualizar') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" value="{{ $user->telefone }}" required>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Salvar Alterações</button>
                        <a href="{{ route('perfil') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection