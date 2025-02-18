<!-- resources/views/layouts/sidebar.blade.php -->
<div class="d-flex">
    <!-- Sidebar -->
    <nav class="bg-dark text-white p-3 d-md-block sidebar" id="sidebar">
        <div class="logo-dash text-center">
            <h4>SIGECOM <small style="font-size: 10px;">Beta</small></h4>
            <small style="font-size: 10px;">Sistema de Gerenciamento de Compras</small>
        </div>
        <hr>
        <ul class="nav flex-column">
            <!-- Home -->
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('home') }}">
                    <i class="bi bi-house-door"></i> Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            <!-- Processos -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle text-white" data-bs-toggle="collapse" href="#processosMenu">
                    <i class="bi bi-folder"></i> Processos
                </a>
                <div class="collapse" id="processosMenu">
                    <ul class="list-unstyled ps-3">
                        <li>
                            <a class="nav-link text-white" href="{{ route('processos.index') }}">
                                <i class="bi bi-list-ul"></i> Todos os Processos
                            </a>
                        </li>
                        <li>
                            <a class="nav-link text-white" href="{{ route('processos.create') }}">
                                <i class="bi bi-plus-circle"></i> Novo Processo
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Relatórios -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle text-white" data-bs-toggle="collapse" href="#relatoriosMenu">
                    <i class="bi bi-bar-chart"></i> Relatórios
                </a>
                <div class="collapse" id="relatoriosMenu">
                    <ul class="list-unstyled ps-3">
                        <li><a class="nav-link text-white" href="#"><i class="bi bi-pie-chart"></i> Relatório 1</a></li>
                        <li><a class="nav-link text-white" href="#"><i class="bi bi-graph-up"></i> Relatório 2</a></li>
                    </ul>
                </div>
            </li>
        </ul>

        <hr>
        <!-- Perfil -->
        <div class="dropdown pb-4 text-center">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                <img src="https://github.com/mdo.png" alt="User" width="30" height="30" class="rounded-circle">
                @auth
                    <span class="d-none d-sm-inline mx-1">{{ Auth::user()->name }}</span>
                @endauth
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                @auth
                <li><a class="dropdown-item" href="{{ route('register') }}">Cadastrar novo usuário</a></li>
                @endauth
                <li><a class="dropdown-item" href="#">Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i> Sair
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Conteúdo -->
    <div class="flex-grow-1 p-4" id="content">
        <button class="btn btn-dark d-md-none" id="toggleSidebar">☰</button>
        
    </div>
</div>

<!-- Script para Toggle Sidebar -->
<script>
    document.getElementById('toggleSidebar').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('collapsed');
        document.getElementById('content').classList.toggle('ml-0');
    });
</script>
