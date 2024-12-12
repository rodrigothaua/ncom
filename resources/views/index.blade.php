@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Processos de Compras</h1>
    <a href="{{ route('processos.create') }}" class="btn btn-primary mb-3">Novo Processo</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Número do Processo</th>
                <th>Descrição</th>
                <th>Data Vigente</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($processos as $processo)
            <tr>
                <td>{{ $processo->numero_processo }}</td>
                <td>{{ $processo->descricao }}</td>
                <td>{{ $processo->data_vigente }}</td>
                <td>
                    <span class="badge" style="background-color: {{ $processo->status }}">{{ ucfirst($processo->status) }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
