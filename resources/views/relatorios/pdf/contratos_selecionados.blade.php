<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
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
        .status-vencido {
            color: #dc3545;
        }
        .status-proximo {
            color: #ffc107;
        }
        .status-ok {
            color: #28a745;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
        .page-break {
            page-break-after: always;
        }
        .detalhes-contrato {
            margin-bottom: 30px;
            padding: 10px;
            border: 1px solid #eee;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <p>Data de Geração: {{ $data_geracao }}</p>
    </div>

    @foreach($contratos as $contrato)
    <div class="detalhes-contrato">
        <h2>Contrato: {{ $contrato->numero_contrato }}</h2>
        <h3>Processo: {{ $contrato->processo->numero_processo }}</h3>
        
        <table>
            <tr>
                <th style="width: 30%">Empresa</th>
                <td>{{ $contrato->nome_empresa_contrato }}</td>
            </tr>
            <tr>
                <th>CNPJ</th>
                <td>{{ $contrato->cnpj_contrato }}</td>
            </tr>
            <tr>
                <th>Telefone</th>
                <td>{{ $contrato->numero_telefone_contrato }}</td>
            </tr>
            <tr>
                <th>Valor do Contrato</th>
                <td>R$ {{ number_format($contrato->valor_contrato, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Período de Vigência</th>
                <td>
                    {{ \Carbon\Carbon::parse($contrato->data_inicial_contrato)->format('d/m/Y') }} a 
                    {{ \Carbon\Carbon::parse($contrato->data_final_contrato)->format('d/m/Y') }}
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @php
                        $hoje = \Carbon\Carbon::now();
                        $dataFinal = \Carbon\Carbon::parse($contrato->data_final_contrato);
                        $diasRestantes = $hoje->diffInDays($dataFinal, false);
                    @endphp
                    
                    @if($diasRestantes < 0)
                        <span class="status-vencido">
                            Vencido há {{ abs($diasRestantes) }} dias
                        </span>
                    @elseif($diasRestantes <= 30)
                        <span class="status-proximo">
                            Vence em menos de 30 dias ({{ $diasRestantes }} dias restantes)
                        </span>
                    @elseif($diasRestantes <= 60)
                        <span class="status-proximo">
                            Vence entre 30-60 dias ({{ $diasRestantes }} dias restantes)
                        </span>
                    @else
                        <span class="status-ok">
                            Em dia ({{ $diasRestantes }} dias restantes)
                        </span>
                    @endif
                </td>
            </tr>
            @if($contrato->observacoes)
            <tr>
                <th>Observações</th>
                <td>{{ $contrato->observacoes }}</td>
            </tr>
            @endif
        </table>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    </div>
    @endforeach

    <div class="footer">
        <p>Página {PAGENUM} de {TOTALPAGENUM} - Relatório gerado em {{ $data_geracao }}</p>
    </div>
</body>
</html>
