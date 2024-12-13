<!-- resources/views/layouts/sidebar.blade.php -->
<div id="sidebar" class="bg-light border-right" style="width: 250px; height: 100vh;">
    <div class="sidebar-header text-center py-3">
        <h3><a href="{{ route('dashboard') }}" class="text-decoration-none text-dark">Ncom Dashboard</a></h3>
    </div>
    <ul class="nav flex-column">
        <!-- Dashboard Link -->
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
        </li>
        <!-- Processos Link -->
        <li class="nav-item">
            <a href="{{ route('processos.index') }}" class="nav-link {{ request()->routeIs('processos.index') ? 'active' : '' }}">
                Processos
            </a>
        </li>
        <!-- Cadastro de Processos Link -->
        <li class="nav-item">
            <a href="{{ route('processos.create') }}" class="nav-link {{ request()->routeIs('processos.create') ? 'active' : '' }}">
                Novo Processo
            </a>
        </li>
    </ul>
</div>
