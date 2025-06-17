@extends('layouts.app')

@section('title', 'Nova Fonte de Orçamento')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Nova Fonte de Orçamento</h1>
                <a href="{{ route('orcamentos.fontes') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('orcamentos.fontes.salvar') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome da Fonte <span class="text-danger">*</span></label>
                                    <input type="text" 
                                        class="form-control @error('nome') is-invalid @enderror" 
                                        id="nome" 
                                        name="nome" 
                                        value="{{ old('nome') }}" 
                                        required
                                        placeholder="Ex: Tesouro Nacional">
                                    @error('nome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="codigo" class="form-label">Código <span class="text-danger">*</span></label>
                                    <input type="text" 
                                        class="form-control @error('codigo') is-invalid @enderror" 
                                        id="codigo" 
                                        name="codigo" 
                                        value="{{ old('codigo') }}" 
                                        required
                                        placeholder="Ex: TN-2025">
                                    @error('codigo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                                    <select class="form-select @error('tipo') is-invalid @enderror" 
                                        id="tipo" 
                                        name="tipo" 
                                        required>
                                        <option value="">Selecione...</option>
                                        <option value="Federal" {{ old('tipo') === 'Federal' ? 'selected' : '' }}>Federal</option>
                                        <option value="Estadual" {{ old('tipo') === 'Estadual' ? 'selected' : '' }}>Estadual</option>
                                        <option value="Municipal" {{ old('tipo') === 'Municipal' ? 'selected' : '' }}>Municipal</option>
                                        <option value="Emenda Parlamentar" {{ old('tipo') === 'Emenda Parlamentar' ? 'selected' : '' }}>Emenda Parlamentar</option>
                                        <option value="Outro" {{ old('tipo') === 'Outro' ? 'selected' : '' }}>Outro</option>
                                    </select>
                                    @error('tipo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ativo" class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                            type="checkbox" 
                                            id="ativo" 
                                            name="ativo" 
                                            value="1" 
                                            {{ old('ativo', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ativo">Fonte Ativa</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                id="descricao" 
                                name="descricao" 
                                rows="3" 
                                placeholder="Descreva detalhes sobre esta fonte de orçamento">{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Componente de Ajuda -->
                        <div class="alert alert-info" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Informações Importantes:</h6>
                            <ul class="mb-0">
                                <li>O código deve ser único para cada fonte de orçamento.</li>
                                <li>O tipo da fonte é importante para relatórios e filtros.</li>
                                <li>Uma fonte inativa não poderá receber novos orçamentos.</li>
                                <li>A descrição ajuda outros usuários a entenderem a origem e finalidade da fonte.</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('orcamentos.fontes') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Salvar Fonte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
