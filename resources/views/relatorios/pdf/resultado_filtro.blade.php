<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório Detalhado</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .summary {
            margin-top: 30px;
            border-top: 2px solid #333;
            padding-top: 20px;
        }
        .filter-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .total {
            font-weight: bold;
            color: #333;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório Detalhado - SIGECOM</h1>
        <p>Data de Geração: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="filter-info">
        <h3>Filtros Aplicados:</h3>
        <ul>
            @if(!empty($filtros['pa_numero']))
                <li>Número PA: {{ $filtros['pa_numero'] }}</li>
            @endif
            @if(!empty($filtros['nd_numero']))
                <li>Número ND: {{ $filtros['nd_numero'] }}</li>
            @endif
            @if(!empty($filtros['empresa']))
                <li>Empresa: {{ $filtros['empresa'] }}</li>
            @endif
            @if(!empty($filtros['data_inicio']))
                <li>Data Inicial: {{ \Carbon\Carbon::parse($filtros['data_inicio'])->format('d/m/Y') }}</li>
            @endif
            @if(!empty($filtros['data_fim']))
                <li>Data Final: {{ \Carbon\Carbon::parse($filtros['data_fim'])->format('d/m/Y') }}</li>
            @endif
            @if(!empty($filtros['modalidade']))
                <li>Modalidade: {{ $filtros['modalidade'] }}</li>
            @endif
        </ul>
    </div>

    @if(isset($contratos))
        <table>
            <thead>
                <tr>
                    <th>Processo</th>
                    <th>Empresa</th>
                    <th>Valor</th>
                    <th>Categorias</th>
                    <th>PA/ND</th>
                </tr>
            </thead>
            <tbody>
                @php $totalGeral = 0; @endphp
                @foreach($contratos as $contrato)
                    @php $totalGeral += $contrato->valor_contrato; @endphp
                    <tr>
                        <td>{{ $contrato->processo->numero_processo }}</td>
                        <td>{{ $contrato->nome_empresa_contrato }}<br>CNPJ: {{ $contrato->cnpj_contrato }}</td>
                        <td>R$ {{ number_format($contrato->valor_contrato, 2, ',', '.') }}</td>
                        <td>
                            @if($contrato->processo->categorias)
                                Consumo: R$ {{ number_format($contrato->processo->categorias->valor_consumo ?? 0, 2, ',', '.') }}<br>
                                Permanente: R$ {{ number_format($contrato->processo->categorias->valor_permanente ?? 0, 2, ',', '.') }}<br>
                                Serviço: R$ {{ number_format($contrato->processo->categorias->valor_servico ?? 0, 2, ',', '.') }}
                            @endif
                        </td>
                        <td>
                            @if($contrato->processo->categorias && $contrato->processo->categorias->detalhesDespesa)
                                @php $detalhes = $contrato->processo->categorias->detalhesDespesa; @endphp
                                @if($detalhes->pa_consumo) PA Consumo: {{ $detalhes->pa_consumo }}<br>@endif
                                @if($detalhes->pa_permanente) PA Permanente: {{ $detalhes->pa_permanente }}<br>@endif
                                @if($detalhes->pa_servico) PA Serviço: {{ $detalhes->pa_servico }}<br>@endif
                                @if($detalhes->nd_consumo) ND Consumo: {{ $detalhes->nd_consumo }}<br>@endif
                                @if($detalhes->nd_permanente) ND Permanente: {{ $detalhes->nd_permanente }}<br>@endif
                                @if($detalhes->nd_servico) ND Serviço: {{ $detalhes->nd_servico }}@endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <h3>Resumo</h3>
            <p>Total de Contratos: {{ $contratos->count() }}</p>
            <p class="total">Valor Total: R$ {{ number_format($totalGeral, 2, ',', '.') }}</p>
        </div>
    @endif

    @if(isset($processos))
        <table>
            <thead>
                <tr>
                    <th>Processo</th>
                    <th>Requisitante</th>
                    <th>Valores por Categoria</th>
                    <th>PA/ND</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $totalConsumo = 0;
                    $totalPermanente = 0;
                    $totalServico = 0;
                @endphp
                @foreach($processos as $processo)
                    @php 
                        $totalConsumo += $processo->categorias->valor_consumo ?? 0;
                        $totalPermanente += $processo->categorias->valor_permanente ?? 0;
                        $totalServico += $processo->categorias->valor_servico ?? 0;
                    @endphp
                    <tr>
                        <td>{{ $processo->numero_processo }}</td>
                        <td>{{ $processo->requisitante }}</td>
                        <td>
                            @if($processo->categorias)
                                Consumo: R$ {{ number_format($processo->categorias->valor_consumo ?? 0, 2, ',', '.') }}<br>
                                Permanente: R$ {{ number_format($processo->categorias->valor_permanente ?? 0, 2, ',', '.') }}<br>
                                Serviço: R$ {{ number_format($processo->categorias->valor_servico ?? 0, 2, ',', '.') }}
                            @endif
                        </td>
                        <td>
                            @if($processo->categorias && $processo->categorias->detalhesDespesa)
                                @php $detalhes = $processo->categorias->detalhesDespesa; @endphp
                                @if($detalhes->pa_consumo) PA Consumo: {{ $detalhes->pa_consumo }}<br>@endif
                                @if($detalhes->pa_permanente) PA Permanente: {{ $detalhes->pa_permanente }}<br>@endif
                                @if($detalhes->pa_servico) PA Serviço: {{ $detalhes->pa_servico }}<br>@endif
                                @if($detalhes->nd_consumo) ND Consumo: {{ $detalhes->nd_consumo }}<br>@endif
                                @if($detalhes->nd_permanente) ND Permanente: {{ $detalhes->nd_permanente }}<br>@endif
                                @if($detalhes->nd_servico) ND Serviço: {{ $detalhes->nd_servico }}@endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <h3>Resumo por Categoria</h3>
            <p>Total de Processos: {{ $processos->count() }}</p>
            <div style="width: 400px; margin: 20px auto;">
                <canvas id="categoriasChart"></canvas>
            </div>
            <p>Total Consumo: R$ {{ number_format($totalConsumo, 2, ',', '.') }}</p>
            <p>Total Permanente: R$ {{ number_format($totalPermanente, 2, ',', '.') }}</p>
            <p>Total Serviço: R$ {{ number_format($totalServico, 2, ',', '.') }}</p>
            <p class="total">Valor Total: R$ {{ number_format($totalConsumo + $totalPermanente + $totalServico, 2, ',', '.') }}</p>

            <script>
                const ctx = document.getElementById('categoriasChart');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Consumo', 'Permanente', 'Serviço'],
                        datasets: [{
                            data: [{{ $totalConsumo }}, {{ $totalPermanente }}, {{ $totalServico }}],
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.8)',
                                'rgba(54, 162, 235, 0.8)',
                                'rgba(255, 206, 86, 0.8)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'Distribuição por Categoria'
                            }
                        }
                    }
                });
            </script>
        </div>
    @endif
</body>
</html>
