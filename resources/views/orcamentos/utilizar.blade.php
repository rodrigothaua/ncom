@extends('layouts.app')

@section('title', 'Registrar Utilização de Orçamento')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3">Registrar Utilização de Orçamento</h1>
                <a href="{{ route('orcamentos.detalhes', $orcamento->id) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Informações do Orçamento e Alocação -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Informações da Alocação</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Orçamento:</dt>
                                <dd class="col-sm-8">{{ $orcamento->numero_orcamento }}</dd>

                                <dt class="col-sm-4">Fonte:</dt>
                                <dd class="col-sm-8">{{ $orcamento->fonte->nome }}</dd>

                                <dt class="col-sm-4">Processo:</dt>
                                <dd class="col-sm-8">{{ $detalhe->processo->numero_processo }}</dd>

                                <dt class="col-sm-4">Categoria:</dt>
                                <dd class="col-sm-8">{{ $detalhe->categoria->nome }}</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Valor Alocado:</dt>
                                <dd class="col-sm-8">R$ {{ number_format($alocacao->valor_alocado, 2, ',', '.') }}</dd>

                                <dt class="col-sm-4">Já Utilizado:</dt>
                                <dd class="col-sm-8">R$ {{ number_format($alocacao->valor_utilizado, 2, ',', '.') }}</dd>

                                <dt class="col-sm-4">Disponível:</dt>
                                <dd class="col-sm-8">R$ {{ number_format($alocacao->valor_alocado - $alocacao->valor_utilizado, 2, ',', '.') }}</dd>

                                <dt class="col-sm-4">Status:</dt>
                                <dd class="col-sm-8">
                                    <span class="badge {{ 
                                        $alocacao->status === 'Planejado' ? 'bg-warning' :
                                        ($alocacao->status === 'Empenhado' ? 'bg-info' :
                                        ($alocacao->status === 'Liquidado' ? 'bg-primary' :
                                        ($alocacao->status === 'Pago' ? 'bg-success' : 'bg-danger')))
                                    }}">
                                        {{ $alocacao->status }}
                                    </span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário de Utilização -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Registrar Utilização</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orcamentos.utilizar.salvar', ['id' => $orcamento->id, 'detalheId' => $detalhe->id]) }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="valor" class="form-label">Valor a Utilizar <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" 
                                            class="form-control @error('valor') is-invalid @enderror" 
                                            id="valor" 
                                            name="valor" 
                                            value="{{ old('valor') }}"
                                            step="0.01"
                                            max="{{ $alocacao->valor_alocado - $alocacao->valor_utilizado }}"
                                            required>
                                        @error('valor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">
                                        Valor máximo disponível: R$ {{ number_format($alocacao->valor_alocado - $alocacao->valor_utilizado, 2, ',', '.') }}
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="numero_empenho" class="form-label">Número do Empenho <span class="text-danger">*</span></label>
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
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
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
                            </div>
                        </div>

                        <!-- Alertas e Informações -->
                        <div class="alert alert-info" role="alert">
                            <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Informações Importantes:</h6>
                            <ul class="mb-0">
                                <li>O valor utilizado não pode exceder o valor disponível na alocação.</li>
                                <li>O registro da utilização não pode ser desfeito após confirmação.</li>
                                <li>O número do empenho deve ser único e condizer com o documento oficial.</li>
                                <li>A data do empenho deve estar dentro do período de vigência do orçamento.</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('orcamentos.detalhes', $orcamento->id) }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Confirmar Utilização
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
    // Validação do valor máximo em tempo real
    document.getElementById('valor').addEventListener('input', function() {
        const valorDisponivel = {{ $alocacao->valor_alocado - $alocacao->valor_utilizado }};
        const valorInserido = parseFloat(this.value);
        
        if (valorInserido > valorDisponivel) {
            this.value = valorDisponivel;
        }
    });

    // Validação da data de empenho
    document.getElementById('data_empenho').addEventListener('change', function() {
        const dataInicio = new Date('{{ $orcamento->data_inicio ?? date('Y-m-d') }}');
        const dataFim = new Date('{{ $orcamento->data_fim ?? date('Y-12-31') }}');
        const dataEmpenho = new Date(this.value);

        if (dataEmpenho < dataInicio || dataEmpenho > dataFim) {
            alert('A data do empenho deve estar dentro do período de vigência do orçamento.');
            this.value = '';
        }
    });
</script>
@endpush
