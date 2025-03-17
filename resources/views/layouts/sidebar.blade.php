<aside id="sidebar" class="js-sidebar">
    <!-- Content For Sidebar -->
    <div class="h-100">
        <div class="sidebar-logo">
            <a href="#">SIGECOM</a>
        </div>
        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Admin Elements
            </li>
            <li class="sidebar-item">
                <a href="{{ route('dashboard') }}" class="sidebar-link">
                    <i class="bi bi-speedometer2"></i>
                        Dashboard
                </a>
            </li>
            <li class="sidebar-item">
                <a href="#" class="sidebar-link collapsed" data-bs-target="#pages" data-bs-toggle="collapse"
                    aria-expanded="false"><i class="fa-solid fa-file-lines pe-2"></i>
                        Processos
                </a>
                <ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <a href="{{ route('processos.index') }}" class="sidebar-link"><i class="bi bi-list-ul"></i> Todos os Processos</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="{{ route('processos.create') }}" class="sidebar-link"><i class="bi bi-plus-circle"></i> Novo processo</a>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="dropdown pb-4 text-center position-absolute bottom-0 w-100">
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
    </div>
</aside>