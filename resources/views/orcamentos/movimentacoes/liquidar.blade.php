@extends('layouts.app')

@section('title', 'Liquidar Alocação - SIGECOM')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Liquidar Alocação</h1>
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
                    <p class="mb-1"><strong>Valor Empenhado:</strong></p>
                    <p>R$ {{ number_format($alocacao->valor_utilizado, 2, ',', '.') }}</p>
                </div>
                <div class="col-md-3">
                    <p class="mb-1"><strong>Nota de Empenho:</strong></p>
                    <p>{{ $alocacao->numero_nota_empenho }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário de Liquidação -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Registrar Liquidação</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('orcamentos.movimentacao.liquidar.salvar', [$alocacao->orcamento_id, $alocacao->id]) }}" 
                method="POST" 
                id="formLiquidacao">
                @csrf

                <div class="row">
                    <!-- Valor -->
                    <div class="col-md-6 mb-3">
                        <label for="valor" class="form-label">Valor <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" 
                                class="form-control @error('valor') is-invalid @enderror" 
                                id="valor" 
                                name="valor" 
                                step="0.01" 
                                min="0.01" 
                                max="{{ $alocacao->valor_utilizado }}"
                                value="{{ old('valor', $alocacao->valor_utilizado) }}" 
                                required>
                        </div>
                        @error('valor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Valor máximo: R$ {{ number_format($alocacao->valor_utilizado, 2, ',', '.') }}</small>
                    </div>

                    <!-- Número do Documento -->
                    <div class="col-md-6 mb-3">
                        <label for="numero_documento" class="form-label">Número do Documento <span class="text-danger">*</span></label>
                        <input type="text" 
                            class="form-control @error('numero_documento') is-invalid @enderror" 
                            id="numero_documento" 
                            name="numero_documento"
                            value="{{ old('numero_documento') }}" 
                            required>
                        @error('numero_documento')
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
                        <i class="bi bi-check-circle"></i> Registrar Liquidação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formLiquidacao');
    const valorInput = document.getElementById('valor');
    const valorMaximo = {{ $alocacao->valor_utilizado }};

    form.addEventListener('submit', function(e) {
        const valor = parseFloat(valorInput.value);
        if (valor > valorMaximo) {
            e.preventDefault();
            alert('O valor liquidado não pode ser maior que o valor empenhado!');
            valorInput.focus();
        }
    });
});
</script>
@endpush
@endsection
