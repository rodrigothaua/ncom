<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #ddd;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            color: #333;
        }
        .header p {
            font-size: 12px;
            color: #666;
            margin: 5px 0 0;
        }
        .info-box {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .info-box h2 {
            font-size: 14px;
            margin: 0 0 10px;
            color: #333;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .info-label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
        }
        .info-value {
            display: table-cell;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .status {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            color: white;
            display: inline-block;
        }
        .status-danger { background-color: #dc3545; }
        .status-warning { background-color: #ffc107; }
        .status-success { background-color: #28a745; }
        .page-break {
            page-break-after: always;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding: 10px 0;
            border-top: 1px solid #ddd;
        }
        .chart-container {
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <p>Gerado em: {{ $data_geracao }}</p>
    </div>

    <!-- Informações do Contrato -->
    <div class="info-box">
        <h2>Informações do Contrato</h2>
        <div class="info-row">
            <span class="info-label">Número do Contrato:</span>
            <span class="info-value">{{ $contrato->numero_contrato }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Empresa:</span>
            <span class="info-value">{{ $contrato->nome_empresa_contrato }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Valor:</span>
            <span class="info-value">R$ {{ number_format($contrato->valor_contrato, 2, ',', '.') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Data Inicial:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($contrato->data_inicial_contrato)->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Data Final:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($contrato->data_final_contrato)->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span class="info-value">
                @php
                    $hoje = \Carbon\Carbon::now();
                    $dataFinal = \Carbon\Carbon::parse($contrato->data_final_contrato);
                    $diasRestantes = $hoje->diffInDays($dataFinal, false);
                @endphp
                
                @if($diasRestantes < 0)
                    <span class="status status-danger">Vencido</span>
                @elseif($diasRestantes <= 30)
                    <span class="status status-warning">Vence em breve</span>
                @else
                    <span class="status status-success">Vigente</span>
                @endif
            </span>
        </div>
    </div>

    <!-- Análise Comparativa -->
    <div class="info-box">
        <h2>Análise Comparativa</h2>
        <table>
            <thead>
                <tr>
                    <th>Indicador</th>
                    <th>Valor</th>
                    <th>Relação com o Contrato</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Menor Valor Registrado</td>
                    <td>R$ {{ number_format($dadosComparativo['menor_valor'], 2, ',', '.') }}</td>
                    <td>
                        @php
                            $diferencaMenor = $contrato->valor_contrato - $dadosComparativo['menor_valor'];
                            $percentualMenor = ($diferencaMenor / $dadosComparativo['menor_valor']) * 100;
                        @endphp
                        {{ $percentualMenor > 0 ? number_format($percentualMenor, 2) . '% maior' : number_format(abs($percentualMenor), 2) . '% menor' }}
                    </td>
                </tr>
                <tr>
                    <td>Média dos Contratos</td>
                    <td>R$ {{ number_format($dadosComparativo['media'], 2, ',', '.') }}</td>
                    <td>
                        @php
                            $diferencaMedia = $contrato->valor_contrato - $dadosComparativo['media'];
                            $percentualMedia = ($diferencaMedia / $dadosComparativo['media']) * 100;
                        @endphp
                        {{ $percentualMedia > 0 ? number_format($percentualMedia, 2) . '% acima' : number_format(abs($percentualMedia), 2) . '% abaixo' }}
                    </td>
                </tr>
                <tr>
                    <td>Maior Valor Registrado</td>
                    <td>R$ {{ number_format($dadosComparativo['maior_valor'], 2, ',', '.') }}</td>
                    <td>
                        @php
                            $diferencaMaior = $contrato->valor_contrato - $dadosComparativo['maior_valor'];
                            $percentualMaior = ($diferencaMaior / $dadosComparativo['maior_valor']) * 100;
                        @endphp
                        {{ $percentualMaior > 0 ? number_format($percentualMaior, 2) . '% maior' : number_format(abs($percentualMaior), 2) . '% menor' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- Processo Vinculado -->
    @if($contrato->processo)
    <div class="info-box">
        <h2>Processo Vinculado</h2>
        <div class="info-row">
            <span class="info-label">Número do Processo:</span>
            <span class="info-value">{{ $contrato->processo->numero_processo }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Data de Entrada:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($contrato->processo->data_entrada)->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Requisitante:</span>
            <span class="info-value">{{ $contrato->processo->requisitante }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Modalidade:</span>
            <span class="info-value">{{ $contrato->processo->modalidade }}</span>
        </div>

        @if($contrato->processo->categorias)
        <h3 style="margin-top: 20px;">Valores por Categoria</h3>
        <table>
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Valor</th>
                    <th>PA</th>
                    <th>ND</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Consumo</td>
                    <td>R$ {{ number_format($contrato->processo->categorias->valor_consumo ?? 0, 2, ',', '.') }}</td>
                    <td>{{ $contrato->processo->categorias->detalhesDespesa->pa_consumo ?? '-' }}</td>
                    <td>{{ $contrato->processo->categorias->detalhesDespesa->nd_consumo ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Permanente</td>
                    <td>R$ {{ number_format($contrato->processo->categorias->valor_permanente ?? 0, 2, ',', '.') }}</td>
                    <td>{{ $contrato->processo->categorias->detalhesDespesa->pa_permanente ?? '-' }}</td>
                    <td>{{ $contrato->processo->categorias->detalhesDespesa->nd_permanente ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Serviço</td>
                    <td>R$ {{ number_format($contrato->processo->categorias->valor_servico ?? 0, 2, ',', '.') }}</td>
                    <td>{{ $contrato->processo->categorias->detalhesDespesa->pa_servico ?? '-' }}</td>
                    <td>{{ $contrato->processo->categorias->detalhesDespesa->nd_servico ?? '-' }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th>R$ {{ number_format($contrato->processo->valor_total ?? 0, 2, ',', '.') }}</th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>
        @endif
    </div>
    @endif

    <div class="footer">
        <p>Página {PAGENO} de {nb}</p>
    </div>
</body>
</html>
