@extends('layouts.app')

@section('title', 'Novo Orçamento')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Novo Orçamento</h1>
                <a href="{{ route('orcamentos.orcamentos') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('orcamentos.salvar') }}" method="POST">
                        @csrf

                        <!-- Fonte e Número do Orçamento -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fonte_orcamento_id" class="form-label">Fonte de Orçamento <span class="text-danger">*</span></label>
                                    <select name="fonte_orcamento_id" id="fonte_orcamento_id" class="form-select @error('fonte_orcamento_id') is-invalid @enderror" required>
                                        <option value="">Selecione uma fonte...</option>
                                        @foreach($fontes as $fonte)
                                            <option value="{{ $fonte->id }}" {{ old('fonte_orcamento_id') == $fonte->id ? 'selected' : '' }}>
                                                {{ $fonte->nome }} ({{ $fonte->codigo }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('fonte_orcamento_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_orcamento" class="form-label">Número do Orçamento <span class="text-danger">*</span></label>
                                    <input type="text" 
                                        class="form-control @error('numero_orcamento') is-invalid @enderror" 
                                        id="numero_orcamento" 
                                        name="numero_orcamento" 
                                        value="{{ old('numero_orcamento') }}" 
                                        required>
                                    @error('numero_orcamento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Valor e Ano -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="valor_total" class="form-label">Valor Total <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" 
                                            class="form-control @error('valor_total') is-invalid @enderror" 
                                            id="valor_total" 
                                            name="valor_total" 
                                            value="{{ old('valor_total') }}" 
                                            step="0.01"
                                            required>
                                    </div>
                                    @error('valor_total')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ano_exercicio" class="form-label">Ano do Exercício <span class="text-danger">*</span></label>
                                    <select name="ano_exercicio" id="ano_exercicio" class="form-select @error('ano_exercicio') is-invalid @enderror" required>
                                        @for ($i = date('Y') - 5; $i <= date('Y') + 5; $i++)
                                            <option value="{{ $i }}" {{ old('ano_exercicio', date('Y')) == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('ano_exercicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Datas -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="data_inicio" class="form-label">Data de Início</label>
                                    <input type="date" 
                                        class="form-control @error('data_inicio') is-invalid @enderror" 
                                        id="data_inicio" 
                                        name="data_inicio" 
                                        value="{{ old('data_inicio') }}">
                                    @error('data_inicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="data_fim" class="form-label">Data de Término</label>
                                    <input type="date" 
                                        class="form-control @error('data_fim') is-invalid @enderror" 
                                        id="data_fim" 
                                        name="data_fim" 
                                        value="{{ old('data_fim') }}">
                                    @error('data_fim')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Informações de Emenda Parlamentar -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="parlamentar" class="form-label">Parlamentar</label>
                                    <input type="text" 
                                        class="form-control @error('parlamentar') is-invalid @enderror" 
                                        id="parlamentar" 
                                        name="parlamentar" 
                                        value="{{ old('parlamentar') }}">
                                    @error('parlamentar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="partido" class="form-label">Partido</label>
                                    <input type="text" 
                                        class="form-control @error('partido') is-invalid @enderror" 
                                        id="partido" 
                                        name="partido" 
                                        value="{{ old('partido') }}">
                                    @error('partido')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Número do Convênio -->
                        <div class="mb-3">
                            <label for="numero_convenio" class="form-label">Número do Convênio</label>
                            <input type="text" 
                                class="form-control @error('numero_convenio') is-invalid @enderror" 
                                id="numero_convenio" 
                                name="numero_convenio" 
                                value="{{ old('numero_convenio') }}">
                            @error('numero_convenio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descrição -->
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                id="descricao" 
                                name="descricao" 
                                rows="3">{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Observações -->
                        <div class="mb-3">
                            <label for="observacoes" class="form-label">Observações</label>
                            <textarea class="form-control @error('observacoes') is-invalid @enderror" 
                                id="observacoes" 
                                name="observacoes" 
                                rows="3">{{ old('observacoes') }}</textarea>
                            @error('observacoes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Componente de Ajuda -->
                        <div class="alert alert-info" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Informações Importantes:</h6>
                            <ul class="mb-0">
                                <li>O número do orçamento deve ser único no sistema.</li>
                                <li>O valor total não poderá ser alterado após a criação.</li>
                                <li>As datas de início e término são opcionais, mas ajudam no controle de vencimentos.</li>
                                <li>Os campos de parlamentar e partido são necessários apenas para emendas parlamentares.</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('orcamentos.orcamentos') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Salvar Orçamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Atualiza campos de parlamentar/partido com base na fonte selecionada
    document.getElementById('fonte_orcamento_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const isParlamentar = selectedOption.text.includes('Emenda Parlamentar');
        
        document.getElementById('parlamentar').parentElement.style.display = isParlamentar ? 'block' : 'none';
        document.getElementById('partido').parentElement.style.display = isParlamentar ? 'block' : 'none';
        
        if (!isParlamentar) {
            document.getElementById('parlamentar').value = '';
            document.getElementById('partido').value = '';
        }
    });
</script>
@endpush
