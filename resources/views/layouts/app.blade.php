<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Outros estilos específicos (se necessário) -->
    @stack('styles')
    
</head>
<body>
    <!-- Inclusão do Navbar -->
    @include('layouts.navbar')

    <div class="container mt-4">
        <!-- Conteúdo da página -->
        @yield('content')
    </div>

    <!-- Adicionando jQuery (requerido pelo Bootstrap para dropdowns) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Adicionando Popper.js (requerido para dropdowns e tooltips do Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>

<!-- Adicionando o JavaScript do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
