<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'NCOM - Login')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- Código para a renderizaçãod o Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Biblioteca IMASK -->
    <script src="https://unpkg.com/imask"></script>

    <!-- Outros estilos específicos (se necessário) -->
    @stack('styles')
    
</head>
<body class="h-100">
    @yield('content')

<!-- Adicionando jQuery (requerido pelo Bootstrap para dropdowns) -->
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

<!-- Javascript main -->
<script src="{{ asset('js/main.js') }}"></script>

<!-- Código para carregar o Chart -->
<script src="{{ asset('js/chart.js') }}"></script>

<!-- Adicionando Popper.js (requerido para dropdowns e tooltips do Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>

<!-- Adicionando o JavaScript do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

</body>
</html>
