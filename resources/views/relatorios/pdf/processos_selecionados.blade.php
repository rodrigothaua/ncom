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
        .detalhes-processo {
            margin-bottom: 30px;
            padding: 10px;
            border: 1px solid #eee;
            background-color: #f9f9f9;
        }
        .categoria-valores {
            margin-top: 10px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #eee;
        }
        .subtitulo {
            background-color: #f5f5f5;
            padding: 5px;
            margin: 10px 0;
            font-weight: bold;
        }
        .valores {
            display: table;
            width: 100%;
        }
        .valor-item {
            margin: 5px 0;
        }
        .valor-label {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <p>Data de Geração: {{ $data_geracao }}</p>
    </div>

    @foreach($processos as $processo)
    <div class="detalhes-processo">
        <h2>Processo: {{ $processo->numero_processo }}</h2>
        
        <table>
            <tr>
                <th style="width: 30%">Requisitante</th>
                <td>{{ $processo->requisitante }}</td>
            </tr>
            <tr>
                <th>Data de Entrada</th>
                <td>{{ \Carbon\Carbon::parse($processo->data_entrada)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Modalidade</th>
                <td>{{ $processo->modalidade ?? 'Não informada' }}</td>
            </tr>
            <tr>
                <th>Procedimentos Auxiliares</th>
                <td>{{ $processo->procedimentos_auxiliares ?? 'Não informado' }}</td>
            </tr>
            <tr>
                <th>Descrição</th>
                <td>{{ $processo->descricao }}</td>
            </tr>
        </table>

        <div class="categoria-valores">
            <div class="subtitulo">Valores por Categoria</div>
            <div class="valores">
                <!-- Consumo -->
                @if($processo->categorias && $processo->categorias->valor_consumo > 0)
                <div class="valor-item">
                    <span class="valor-label">Consumo:</span>
                    <span>R$ {{ number_format($processo->categorias->valor_consumo, 2, ',', '.') }}</span>
                    @if($processo->consumo_despesa)
                        <br>
                        <small>PA: {{ $processo->consumo_despesa['numero_pa'] ?? '-' }}</small>
                        <br>
                        <small>ND: {{ $processo->consumo_despesa['natureza_despesa'] ?? '-' }}</small>
                    @endif
                </div>
                @endif

                <!-- Permanente -->
                @if($processo->categorias && $processo->categorias->valor_permanente > 0)
                <div class="valor-item">
                    <span class="valor-label">Permanente:</span>
                    <span>R$ {{ number_format($processo->categorias->valor_permanente, 2, ',', '.') }}</span>
                    @if($processo->permanente_despesa)
                        <br>
                        <small>PA: {{ $processo->permanente_despesa['numero_pa'] ?? '-' }}</small>
                        <br>
                        <small>ND: {{ $processo->permanente_despesa['natureza_despesa'] ?? '-' }}</small>
                    @endif
                </div>
                @endif

                <!-- Serviço -->
                @if($processo->categorias && $processo->categorias->valor_servico > 0)
                <div class="valor-item">
                    <span class="valor-label">Serviço:</span>
                    <span>R$ {{ number_format($processo->categorias->valor_servico, 2, ',', '.') }}</span>
                    @if($processo->servico_despesa)
                        <br>
                        <small>PA: {{ $processo->servico_despesa['numero_pa'] ?? '-' }}</small>
                        <br>
                        <small>ND: {{ $processo->servico_despesa['natureza_despesa'] ?? '-' }}</small>
                    @endif
                </div>
                @endif

                <div class="valor-item" style="margin-top: 10px; border-top: 1px solid #ddd; padding-top: 10px;">
                    <span class="valor-label">Valor Total:</span>
                    <span>R$ {{ number_format($processo->valor_total, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>

        @if($processo->contratos->count() > 0)
        <div class="categoria-valores">
            <div class="subtitulo">Contratos Vinculados</div>
            <table>
                <thead>
                    <tr>
                        <th>Nº Contrato</th>
                        <th>Empresa</th>
                        <th>Valor</th>
                        <th>Vigência</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($processo->contratos as $contrato)
                    <tr>
                        <td>{{ $contrato->numero_contrato }}</td>
                        <td>{{ $contrato->nome_empresa_contrato }}</td>
                        <td>R$ {{ number_format($contrato->valor_contrato, 2, ',', '.') }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($contrato->data_inicial_contrato)->format('d/m/Y') }} a 
                            {{ \Carbon\Carbon::parse($contrato->data_final_contrato)->format('d/m/Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
    @endforeach

    <div class="footer">
        <p>Página {PAGENUM} de {TOTALPAGENUM} - Relatório gerado em {{ $data_geracao }}</p>
    </div>
</body>
</html>
