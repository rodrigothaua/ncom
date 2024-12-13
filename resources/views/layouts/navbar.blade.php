<!-- resources/views/layouts/navbar.blade.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <!-- Logo ou título -->
        <a class="navbar-brand" href="{{ route('dashboard') }}">Ncom Dashboard</a>
        <!-- Botão de toggle para dispositivos móveis -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Dashboard Link -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <!-- Processos Link -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('processos.index') ? 'active' : '' }}" href="{{ route('processos.index') }}">Processos</a>
                </li>
                <!-- Cadastro de Processos Link -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('processos.create') ? 'active' : '' }}" href="{{ route('processos.create') }}">Novo Processo</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
