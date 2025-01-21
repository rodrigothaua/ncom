@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="mb-4">Dashboard</h1>
<a href="{{ route('dashboard.create') }}" class="btn btn-primary mb-3">Novo Processo</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Valor Total</th>
            <th>Data Início</th>
            <th>Data Fim</th>
        </tr>
    </thead>
    <tbody>
        @foreach($processos as $processo)
        <tr>
            <td>{{ $processo->nome }}</td>
            <td>{{ ucfirst($processo->categoria) }}</td>
            <td>R$ {{ number_format($processo->valor_total, 2, ',', '.') }}</td>
            <td>{{ $processo->data_inicio }}</td>
            <td>{{ $processo->data_fim }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
