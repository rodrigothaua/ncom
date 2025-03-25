@extends('layouts.app')

@section('title', 'Meu Perfil - NCOM')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
@include('layouts.partials.alerts')
<div class="container-fluid">
    <h1 class="mb-4">Meu Perfil</h1>

    <div class="row">
        <div class="col-lg-4 col-md-12">
            <div class="card mb-4">
                <div class="card-body text-center">
                    @if(Auth::user()->profile_photo)
                        <img src="{{ asset('storage/profile-photos/' . Auth::user()->profile_photo) }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="rounded-circle img-fluid" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <img src="https://github.com/mdo.png" 
                             alt="{{ Auth::user()->name }}" 
                             class="rounded-circle img-fluid" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @endif
                    <h5 class="my-3">{{ Auth::user()->name }}</h5>
                    <p class="text-muted mb-1">{{ Auth::user()->role ?? 'Usuário' }}</p>
                    <p class="text-muted mb-4">{{ Auth::user()->email }}</p>
                    <div class="d-flex justify-content-center mb-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Editar Perfil</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8 col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informações do Perfil</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Nome Completo</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ Auth::user()->name }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">E-mail</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Função</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ Auth::user()->role ?? 'Usuário' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Conta criada em</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{ Auth::user()->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
