<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-funnel"></i> Filtros</h5>
        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#filtrosCollapse">
            <i class="bi bi-chevron-down"></i>
        </button>
    </div>
    <div class="collapse" id="filtrosCollapse">
        <div class="card-body">
            <form method="GET" action="{{ request()->url() }}" id="filtroForm">
                <div class="row g-3">
                    <!-- Período -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Período</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Data Inicial</label>
                                        <input type="date" class="form-control" name="data_inicio" value="{{ request('data_inicio') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Data Final</label>
                                        <input type="date" class="form-control" name="data_fim" value="{{ request('data_fim') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Status do Contrato</h6>
                            </div>
                            <div class="card-body">
                                <select class="form-select" name="status">
                                    <option value="">Todos os Status</option>
                                    <option value="vencido" {{ request('status') == 'vencido' ? 'selected' : '' }}>Vencido</option>
                                    <option value="menos30" {{ request('status') == 'menos30' ? 'selected' : '' }}>Vence em -30 dias</option>
                                    <option value="30a60" {{ request('status') == '30a60' ? 'selected' : '' }}>Vence entre 30 e 60 dias</option>
                                    <option value="60a90" {{ request('status') == '60a90' ? 'selected' : '' }}>Vence entre 60 e 90 dias</option>
                                    <option value="90a180" {{ request('status') == '90a180' ? 'selected' : '' }}>Vence entre 90 e 180 dias</option>
                                    <option value="mais180" {{ request('status') == 'mais180' ? 'selected' : '' }}>Vence em +180 dias</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Empresa -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Dados da Empresa</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label">Nome da Empresa</label>
                                    <input type="text" class="form-control" name="empresa" placeholder="Digite o nome da empresa" value="{{ request('empresa') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">CNPJ</label>
                                    <input type="text" class="form-control" name="cnpj" placeholder="00.000.000/0000-00" value="{{ request('cnpj') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Valores -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Valores</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Valor Mínimo</label>
                                        <input type="text" class="form-control money-mask" name="valor_min" placeholder="R$ 0,00" value="{{ request('valor_min') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Valor Máximo</label>
                                        <input type="text" class="form-control money-mask" name="valor_max" placeholder="R$ 0,00" value="{{ request('valor_max') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Categorias -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Valores por Categoria</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Consumo</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control money-mask" name="valor_consumo_min" placeholder="R$ 0,00" value="{{ request('valor_consumo_min') }}">
                                            <input type="text" class="form-control money-mask" name="valor_consumo_max" placeholder="R$ 0,00" value="{{ request('valor_consumo_max') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Permanente</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control money-mask" name="valor_permanente_min" placeholder="R$ 0,00" value="{{ request('valor_permanente_min') }}">
                                            <input type="text" class="form-control money-mask" name="valor_permanente_max" placeholder="R$ 0,00" value="{{ request('valor_permanente_max') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Serviço</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control money-mask" name="valor_servico_min" placeholder="R$ 0,00" value="{{ request('valor_servico_min') }}">
                                            <input type="text" class="form-control money-mask" name="valor_servico_max" placeholder="R$ 0,00" value="{{ request('valor_servico_max') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PA e ND -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Números PA e ND</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label">Número PA</label>
                                    <input type="text" class="form-control pa-mask" name="pa_numero" placeholder="00.000.0000.0000" value="{{ request('pa_numero') }}">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Número ND</label>
                                    <input type="text" class="form-control" name="nd_numero" placeholder="0.0.00.00" value="{{ request('nd_numero') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modalidade e Procedimentos -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Modalidade e Procedimentos</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label">Modalidade</label>
                                    <select class="form-select" name="modalidade">
                                        <option value="">Todas as Modalidades</option>
                                        <option value="PREGÃO" {{ request('modalidade') == 'PREGÃO' ? 'selected' : '' }}>Pregão</option>
                                        <option value="CONCORRÊNCIA" {{ request('modalidade') == 'CONCORRÊNCIA' ? 'selected' : '' }}>Concorrência</option>
                                        <!-- Adicione outras modalidades conforme necessário -->
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Procedimentos Auxiliares</label>
                                    <select class="form-select" name="procedimentos">
                                        <option value="">Todos os Procedimentos</option>
                                        <option value="SISTEMA DE REGISTRO DE PREÇÕS" {{ request('procedimentos') == 'SISTEMA DE REGISTRO DE PREÇÕS' ? 'selected' : '' }}>Sistema de Registro de Preços</option>
                                        <option value="PRÉ-QUALIFICADO" {{ request('procedimentos') == 'PRÉ-QUALIFICADO' ? 'selected' : '' }}>Pré-Qualificado</option>
                                        <!-- Adicione outros procedimentos conforme necessário -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <a href="{{ request()->url() }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Limpar Filtros
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel"></i> Aplicar Filtros
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@media print {
    #filtrosCollapse, .no-print {
        display: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Se houver qualquer filtro aplicado, expande a seção de filtros
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.toString()) {
        document.getElementById('filtrosCollapse').classList.add('show');
    }

    // Aplicar máscaras aos campos
    $('.pa-mask').mask('00.000.0000.0000');
    $('input[name="cnpj"]').mask('00.000.000/0000-00');
    $('input[name="nd_numero"]').mask('0.0.00.00');
    
    // Máscaras para valores monetários
    const moneyInputs = [
        'input[name="valor_min"]',
        'input[name="valor_max"]',
        'input[name="valor_consumo_min"]',
        'input[name="valor_consumo_max"]',
        'input[name="valor_permanente_min"]',
        'input[name="valor_permanente_max"]',
        'input[name="valor_servico_min"]',
        'input[name="valor_servico_max"]'
    ];
    
    moneyInputs.forEach(selector => {
        $(selector).mask('#.##0,00', {reverse: true});
        $(selector).attr('placeholder', 'R$ 0,00');
    });
});
</script>
