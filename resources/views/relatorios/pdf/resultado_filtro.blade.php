<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório - SIGECOM</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
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
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .filtros {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #eee;
        }
        .filtros strong {
            color: #333;
        }
        .status-vencido {
            color: #dc3545;
        }
        .status-proximo {
            color: #ffc107;
        }
        .status-ok {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório - SIGECOM</h1>
        <p>{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
        @if($tipo == 'geral')
            <p>Filtro Geral de Processos</p>
        @elseif($tipo == 'vencimento')
            <p>Contratos por Vencimento</p>
        @elseif($tipo == 'valor')
            <p>Contratos por Valor</p>
        @else
            <p>Categorias por Processo</p>
        @endif
    </div>

    @if(isset($filtros) && count($filtros) > 0)
    <div class="filtros">
        <h3>Filtros Aplicados:</h3>
        @foreach($filtros as $key => $value)
            @if($value && !str_contains($key, '_token') && !str_contains($key, 'pdf'))
                <p><strong>{{ str_replace('_', ' ', ucfirst($key)) }}:</strong> {{ $value }}</p>
            @endif
        @endforeach
    </div>
    @endif

    @if($tipo == 'geral' && isset($resultados))
        <table>
            <thead>
                <tr>
                    <th>Nº Processo</th>
                    <th>Requisitante</th>
                    <th>Data Entrada</th>
                    <th>Valor Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resultados as $processo)
                <tr>
                    <td>{{ $processo->numero_processo }}</td>
                    <td>{{ $processo->requisitante }}</td>
                    <td>{{ \Carbon\Carbon::parse($processo->data_entrada)->format('d/m/Y') }}</td>
                    <td>R$ {{ number_format($processo->valor_total, 2, ',', '.') }}</td>
                    <td>
                        @if($processo->contratos->count() > 0)
                            @php
                                $hoje = \Carbon\Carbon::now();
                                $dataFinal = \Carbon\Carbon::parse($processo->contratos->first()->data_final_contrato);
                                $diasRestantes = $hoje->diffInDays($dataFinal, false);
                            @endphp
                            
                            @if($diasRestantes < 0)
                                <span class="status-vencido">Vencido</span>
                            @elseif($diasRestantes <= 30)
                                <span class="status-proximo">Vence em menos de 30 dias</span>
                            @else
                                <span class="status-ok">Em dia</span>
                            @endif
                        @else
                            Sem contrato
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($tipo == 'vencimento' && isset($contratos))
        <table>
            <thead>
                <tr>
                    <th>Nº Contrato</th>
                    <th>Nº Processo</th>
                    <th>Data Vencimento</th>
                    <th>Valor</th>
                    <th>Status</th>
                    <th>Dias Restantes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contratos as $contrato)
                <tr>
                    <td>{{ $contrato->numero_contrato }}</td>
                    <td>{{ $contrato->processo->numero_processo }}</td>
                    <td>{{ \Carbon\Carbon::parse($contrato->data_final_contrato)->format('d/m/Y') }}</td>
                    <td>R$ {{ number_format($contrato->valor_contrato, 2, ',', '.') }}</td>
                    <td>
                        @php
                            $hoje = \Carbon\Carbon::now();
                            $dataFinal = \Carbon\Carbon::parse($contrato->data_final_contrato);
                            $diasRestantes = $hoje->diffInDays($dataFinal, false);
                        @endphp
                        
                        @if($diasRestantes < 0)
                            <span class="status-vencido">Vencido</span>
                        @elseif($diasRestantes <= 30)
                            <span class="status-proximo">Vence em menos de 30 dias</span>
                        @else
                            <span class="status-ok">Em dia</span>
                        @endif
                    </td>
                    <td>
                        @if($diasRestantes < 0)
                            <span class="status-vencido">Vencido há {{ abs($diasRestantes) }} dias</span>
                        @else
                            <span class="status-ok">{{ $diasRestantes }} dias restantes</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($tipo == 'valor' && isset($contratos))
        <table>
            <thead>
                <tr>
                    <th>Nº Contrato</th>
                    <th>Nº Processo</th>
                    <th>Empresa</th>
                    <th>Valor</th>
                    <th>Data Inicial</th>
                    <th>Data Final</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contratos as $contrato)
                <tr>
                    <td>{{ $contrato->numero_contrato }}</td>
                    <td>{{ $contrato->processo->numero_processo }}</td>
                    <td>{{ $contrato->nome_empresa_contrato }}</td>
                    <td>R$ {{ number_format($contrato->valor_contrato, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($contrato->data_inicial_contrato)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($contrato->data_final_contrato)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @elseif($tipo == 'categorias' && isset($processos))
        <table>
            <thead>
                <tr>
                    <th>Nº Processo</th>
                    <th>Consumo</th>
                    <th>Permanente</th>
                    <th>Serviço</th>
                    <th>Total</th>
                    <th>Data Entrada</th>
                </tr>
            </thead>
            <tbody>
                @foreach($processos as $processo)
                <tr>
                    <td>{{ $processo->numero_processo }}</td>
                    <td>R$ {{ number_format($processo->categorias->valor_consumo ?? 0, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($processo->categorias->valor_permanente ?? 0, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($processo->categorias->valor_servico ?? 0, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($processo->valor_total ?? 0, 2, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($processo->data_entrada)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Relatório gerado em {{ \Carbon\Carbon::now()->format('d/m/Y \à\s H:i:s') }}</p>
    </div>
</body>
</html>
