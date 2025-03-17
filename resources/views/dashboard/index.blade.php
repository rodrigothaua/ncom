@extends('layouts.app')

@section('title', 'Dashboard - SIGECOM')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active" data-bs-interval="10000">
                            <img src="{{ asset('img/slider-1.jpg') }}" class="d-block w-100" alt="..." height="300">
                        </div>
                        <div class="carousel-item" data-bs-interval="10000">
                            <img src="{{ asset('img/slider-1.jpg') }}" class="d-block w-100" alt="..." height="300">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    <button id="closeCarousel" class="btn btn-danger btn-sm close-carousel-btn">
                        <i class="bi bi-x-lg"></i> Fechar
                    </button>
                </div><br>
            </div>
            <div class="col-12 col-md-6 d-flex">
                <div class="card flex-fill border-dark illustration">
                    <div class="card-body p-0 d-flex flex-fill">
                        <div class="row g-0 w-100">
                            <div class="col-6">
                                <div class="p-3 m-1">
                                    <h4>Bem-vindo, {{ auth()->user()->name ?? 'Usuário' }}!</h4>
                                    <p class="mb-0">Painel Administrativo.</p>
                                </div>
                            </div>
                            <div class="col-6 align-self-end text-end">
                                <img src="image/customer-support.jpg" class="img-fluid illustration-img" alt="Illustration">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 d-flex">
                <div class="card flex-fill border-dark">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-start">
                            <div class="flex-grow-1">
                                <h4 class="mb-2">
                                    R$ {{ number_format($totalAtual, 2, ',', '.') }}
                                </h4>
                                <p class="mb-2">
                                    Valor global de contratos
                                </p>
                                <div class="mb-0">
                                    <span class="badge {{ $porcentagem >= 0 ? 'text-success' : 'text-danger' }} me-2">
                                        {{ $porcentagem >= 0 ? '+' : '' }}{{ number_format($porcentagem, 2) }}%
                                    </span>
                                    <span class="text-muted">
                                        Desde o mês passado
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fechar slide -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('carouselExample');
            const closeButton = document.getElementById('closeCarousel');

            closeButton.addEventListener('click', function() {
                carousel.style.display = 'none';
            });
        });
    </script>
@endsection