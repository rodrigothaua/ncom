@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Gerenciamento de Processos</h1>
    
    <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalNovoProcesso">Novo Processo</a>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Número do Processo</th>
                <th scope="col">Descrição</th>
                <th scope="col">Data Vigente</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
                @foreach($processos as $processo)
                    @php
                        // Definindo a classe da cor com base no status
                        if ($processo->status === 'Vermelho') {
                            $rowClass = 'table-danger';
                        } elseif ($processo->status === 'Amarelo') {
                            $rowClass = 'table-warning';
                        } elseif ($processo->status === 'Verde') {
                            $rowClass = 'table-success';
                        } elseif ($processo->status === 'Laranja') {
                            $rowClass = 'table-info';
                        } else {
                            $rowClass = 'table-light';
                        }
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <th>{{ $processo->id}}</th>
                        <td>{{ $processo->numero_processo }}</td>
                        <td>{{ $processo->descricao }}</td>
                        <td>{{ \Carbon\Carbon::parse($processo->data_vigente)->format('d/m/Y') }}</td>
                        <td>{{ $processo->status }}</td>
                    </tr>
                @endforeach
        </tbody>
    </table>
</div>

<!-- Modal para Novo Processo -->
<div class="modal fade" id="modalNovoProcesso" tabindex="-1" aria-labelledby="modalNovoProcessoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('processos.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNovoProcessoLabel">Novo Processo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="numero_processo" class="form-label">Número do Processo</label>
                        <input type="text" name="numero_processo" class="form-control" id="numero_processo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea name="descricao" class="form-control" id="descricao" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="data_vigente" class="form-label">Data Vigente</label>
                        <input type="date" name="data_vigente" class="form-control" id="data_vigente" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
