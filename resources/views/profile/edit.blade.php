@extends('layouts.app')

@section('title', 'Editar Perfil - NCOM')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
@include('layouts.partials.alerts')
<div class="container-fluid">
    <h1 class="mb-4">Editar Perfil</h1>

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informações Pessoais</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3 text-center">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('storage/profile-photos/' . Auth::user()->profile_photo) }}" 
                                     alt="{{ Auth::user()->name }}" 
                                     class="rounded-circle img-fluid mb-3" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <img src="https://github.com/mdo.png" 
                                     alt="{{ Auth::user()->name }}" 
                                     class="rounded-circle img-fluid mb-3" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @endif
                            <div class="mb-3">
                                <label for="profile_photo" class="form-label">Foto de Perfil</label>
                                <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" 
                                       id="profile_photo" name="profile_photo">
                                @error('profile_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Imagem de até 1MB. Formatos: JPG, PNG, GIF.</div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', Auth::user()->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', Auth::user()->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Função</label>
                            <input type="text" class="form-control" id="role" 
                                   value="{{ Auth::user()->role ?? 'Usuário' }}" disabled>
                            <div class="form-text">A função não pode ser alterada pelo usuário.</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Alterar Senha</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Senha Atual</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Atualizar Senha</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
