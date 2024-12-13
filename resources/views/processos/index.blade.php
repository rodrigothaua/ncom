<!-- resources/views/processos/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="my-4">Gerenciamento de Processos</h1>

        <!-- Exibe uma mensagem de sucesso, se houver -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Botão para abrir o modal de criação -->
        <button class="btn btn-primary" data-toggle="modal" data-target="#processoModal">Novo Processo</button>

        <!-- Tabela com os processos -->
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Número do Processo</th>
                    <th>Descrição</th>
                    <th>Data Vigente</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($processos as $processo)
                    <tr>
                        <td>{{ $processo->id }}</td>
                        <td>{{ $processo->numero_processo }}</td>
                        <td>{{ $processo->descricao }}</td>
                        <td>{{ \Carbon\Carbon::parse($processo->data_vigente)->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge 
                                @if($processo->status == 'verde') bg-success 
                                @elseif($processo->status == 'amarelo') bg-warning 
                                @elseif($processo->status == 'vermelho') bg-danger 
                                @endif">
                                {{ ucfirst($processo->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal para criar novo processo -->
    <div class="modal fade" id="processoModal" tabindex="-1" aria-labelledby="processoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="processoModalLabel">Novo Processo de Compra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="processoForm" method="POST" action="{{ route('processos.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="numero">Número do Processo</label>
                            <input type="text" class="form-control" id="numero" name="numero" required>
                        </div>

                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                        </div>

                        <div class="form-group">
                            <label for="data_vigente">Data Vigente</label>
                            <input type="date" class="form-control" id="data_vigente" name="data_vigente" required>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success">Salvar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
