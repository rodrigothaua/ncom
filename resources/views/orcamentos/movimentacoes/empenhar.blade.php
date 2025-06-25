@extends('layouts.app')

@section('title', 'Empenhar Alocação - SIGECOM')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Empenhar Alocação</h1>
        <a href="{{ route('orcamentos.detalhes', $alocacao->orcamento_id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <!-- Informações da Alocação -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Informações da Alocação</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p class="mb-1"><strong>Orçamento:</strong></p>
                    <p>{{ $alocacao->orcamento->numero_orcamento }}</p>
                </div>
                <div class="col-md-3">
                    <p class="mb-1"><strong>Categoria:</strong></p>
                    <p>{{ $alocacao->categoria->nome }}</p>
                </div>
                <div class="col-md-3">
                    <p class="mb-1"><strong>Valor Alocado:</strong></p>
                    <p>R$ {{ number_format($alocacao->valor_alocado, 2, ',', '.') }}</p>
                </div>
                <div class="col-md-3">
                    <p class="mb-1"><strong>Valor Disponível:</strong></p>
                    <p>R$ {{ number_format($alocacao->valor_disponivel, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário de Empenho -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Registrar Empenho</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('orcamentos.movimentacao.empenhar.salvar', [$alocacao->orcamento_id, $alocacao->id]) }}" 
                method="POST" 
                id="formEmpenho">
                @csrf

                <div class="row">
                    <!-- Valor -->
                    <div class="col-md-4 mb-3">
                        <label for="valor" class="form-label">Valor <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" 
                                class="form-control @error('valor') is-invalid @enderror" 
                                id="valor" 
                                name="valor" 
                                step="0.01" 
                                min="0.01" 
                                max="{{ $alocacao->valor_disponivel }}"
                                value="{{ old('valor') }}" 
                                required>
                        </div>
                        @error('valor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Valor máximo disponível: R$ {{ number_format($alocacao->valor_disponivel, 2, ',', '.') }}</small>
                    </div>

                    <!-- Número da Nota de Empenho -->
                    <div class="col-md-4 mb-3">
                        <label for="numero_empenho" class="form-label">Número da Nota de Empenho <span class="text-danger">*</span></label>
                        <input type="text" 
                            class="form-control @error('numero_empenho') is-invalid @enderror" 
                            id="numero_empenho" 
                            name="numero_empenho"
                            value="{{ old('numero_empenho') }}" 
                            required>
                        @error('numero_empenho')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Data do Empenho -->
                    <div class="col-md-4 mb-3">
                        <label for="data_empenho" class="form-label">Data do Empenho <span class="text-danger">*</span></label>
                        <input type="date" 
                            class="form-control @error('data_empenho') is-invalid @enderror" 
                            id="data_empenho" 
                            name="data_empenho"
                            value="{{ old('data_empenho', date('Y-m-d')) }}" 
                            required>
                        @error('data_empenho')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Observações -->
                    <div class="col-md-12 mb-3">
                        <label for="observacoes" class="form-label">Observações</label>
                        <textarea name="observacoes" id="observacoes" rows="3" 
                            class="form-control @error('observacoes') is-invalid @enderror">{{ old('observacoes') }}</textarea>
                        @error('observacoes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Registrar Empenho
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEmpenho');
    const valorInput = document.getElementById('valor');
    const valorMaximo = {{ $alocacao->valor_disponivel }};

    form.addEventListener('submit', function(e) {
        const valor = parseFloat(valorInput.value);
        if (valor > valorMaximo) {
            e.preventDefault();
            alert('O valor empenhado não pode ser maior que o valor disponível!');
            valorInput.focus();
        }
    });
});
</script>
@endpush
@endsection
