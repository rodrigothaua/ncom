@extends('layouts.app')

@section('title', 'Alocar Orçamento')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Alocar Orçamento</h1>
                <a href="{{ route('orcamentos.detalhes', $orcamento->id) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Informações do Orçamento -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Informações do Orçamento</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6>Número do Orçamento</h6>
                            <p class="mb-0">{{ $orcamento->numero_orcamento }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6>Fonte</h6>
                            <p class="mb-0">{{ $orcamento->fonte->nome }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6>Valor Total</h6>
                            <p class="mb-0">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</p>
                        </div>
                        <div class="col-md-3">
                            <h6>Disponível</h6>
                            <p class="mb-0 {{ $orcamento->valor_disponivel < 0 ? 'text-danger' : '' }}">
                                R$ {{ number_format($orcamento->valor_disponivel, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário de Alocação -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Alocar para Detalhe de Despesa</h5>
                </div>
                <div class="card-body">
                    @if($detalhes->isEmpty())
                        <div class="alert alert-info">
                            Não há detalhes de despesa disponíveis para alocação.
                        </div>
                    @else
                        <form action="{{ route('orcamentos.alocar.salvar', $orcamento->id) }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="detalhes_despesa_id" class="form-label">Detalhe de Despesa <span class="text-danger">*</span></label>
                                        <select name="detalhes_despesa_id" id="detalhes_despesa_id" class="form-select @error('detalhes_despesa_id') is-invalid @enderror" required>
                                            <option value="">Selecione um detalhe...</option>
                                            @foreach($detalhes as $detalhe)
                                                <option value="{{ $detalhe->id }}" {{ old('detalhes_despesa_id') == $detalhe->id ? 'selected' : '' }}>
                                                    Processo: {{ $detalhe->processo->numero_processo }} - 
                                                    {{ $detalhe->categoria->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('detalhes_despesa_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="valor" class="form-label">Valor a Alocar <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="number" 
                                                class="form-control @error('valor') is-invalid @enderror" 
                                                id="valor" 
                                                name="valor" 
                                                value="{{ old('valor') }}"
                                                step="0.01"
                                                max="{{ $orcamento->valor_disponivel }}"
                                                required>
                                            @error('valor')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="text-muted">
                                            Valor máximo disponível: R$ {{ number_format($orcamento->valor_disponivel, 2, ',', '.') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

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

                            <!-- Alertas e Informações -->
                            <div class="alert alert-info" role="alert">
                                <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Informações Importantes:</h6>
                                <ul class="mb-0">
                                    <li>O valor alocado não pode exceder o valor disponível no orçamento.</li>
                                    <li>Uma vez alocado, o valor só poderá ser alterado pela administração.</li>
                                    <li>A alocação ficará com status "Planejado" até que seja realizado um empenho.</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('orcamentos.detalhes', $orcamento->id) }}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Confirmar Alocação
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Validação do valor máximo em tempo real
    document.getElementById('valor').addEventListener('input', function() {
        const valorDisponivel = {{ $orcamento->valor_disponivel }};
        const valorInserido = parseFloat(this.value);
        
        if (valorInserido > valorDisponivel) {
            this.value = valorDisponivel;
        }
    });
</script>
@endpush
