<div class="d-flex">
    <!-- Sidebar -->
    <nav class="bg-dark text-white p-3 d-none d-md-block" id="sidebar" style="width: 250px; height: 100vh; position: fixed; overflow-y: auto;">
        <div class="logo-dash">
            <h4 class="text-left">SIGECOM  <small style="font-size: 10px;">Beta</small></h4>
            <small style="font-size: 10px;">Sistema de Gerenciamento de Compras</small>
        </div>
        <ul class="nav flex-column">
            <!-- Link Home -->
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

            <!-- Dropdown de Processos -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle text-white" data-bs-toggle="collapse" href="#processosMenu" role="button" aria-expanded="false" aria-controls="processosMenu">
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

            <!-- Dropdown de Relatórios -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle text-white" data-bs-toggle="collapse" href="#relatoriosMenu" role="button" aria-expanded="false" aria-controls="relatoriosMenu">
                    <i class="bi bi-bar-chart"></i> Relatórios
                </a>
                <div class="collapse" id="relatoriosMenu">
                    <ul class="list-unstyled ps-3">
                        <li>
                            <a class="nav-link text-white" href="#">
                                <i class="bi bi-pie-chart"></i> Relatório 1
                            </a>
                        </li>
                        <li>
                            <a class="nav-link text-white" href="#">
                                <i class="bi bi-graph-up"></i> Relatório 2
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        <hr>
        <div class="dropdown pb-4">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://github.com/mdo.png" alt="hugenerd" width="30" height="30" class="rounded-circle">
                @auth
                    <span class="d-none d-sm-inline mx-1">{{ Auth::user()->name }}</span>
                @endauth
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                @auth
                <li><a class="dropdown-item" href="{{ route('register') }}">Cadastrar novo usuario</a></li>
                @endauth
                <li><a class="dropdown-item" href="#">Perfil</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a class="dropdown-item" href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bi bi-box-arrow-right"></i>
                        Sair
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Conteúdo -->
    <div class="flex-grow-1 ms-3" style="margin-left: 250px;">
        <!-- Conteúdo da página vai aqui -->
    </div>
</div>
