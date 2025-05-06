<!DOCTYPE html>
<html>
<head>
    <title>Relatório de Filtro Geral</title>
    <style>
        /* Estilos CSS para o seu PDF */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .contract-info {
            margin-top: 10px;
            border: 1px solid #eee;
            padding: 8px;
        }
        .contract-info h6 {
            margin-top: 0;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <h1>Relatório de Filtro Geral</h1>
    <p>Gerado em: {{ $data_geracao }}</p>
    <p>Tipo de Relatório: {{ ucfirst($tipo) }}</p>

    @if($resultados->isNotEmpty())
        @foreach($resultados as $processo)
            <table>
                <thead>
                    <tr>
                        <th>Número do Processo</th>
                        <th>Requisitante</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $processo->numero_processo }}</td>
                        <td>{{ $processo->requisitante }}</td>
                        <td>{{ $processo->descricao }}</td>
                    </tr>
                </tbody>
            </table>

            <table>
                <thead>
                    <tr>
                        <th>Valor Consumo</th>
                        <th>Valor Permanente</th>
                        <th>Valor Serviço</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ isset($processo->categorias) ? 'R$ ' . number_format($processo->categorias->sum('valor_consumo') ?? 0, 2, ',', '.') : 'Não informado' }}</td>
                        <td>{{ isset($processo->categorias) ? 'R$ ' . number_format($processo->categorias->sum('valor_permanente') ?? 0, 2, ',', '.') : 'Não informado' }}</td>
                        <td>{{ isset($processo->categorias) ? 'R$ ' . number_format($processo->categorias->sum('valor_servico') ?? 0, 2, ',', '.') : 'Não informado' }}</td>
                    </tr>
                </tbody>
            </table>

            @if($processo->contratos->isNotEmpty())
                @foreach($processo->contratos as $contrato)
                    <div class="contract-info">
                        <h6>Informações do Contrato:</h6>
                        <p><strong>Nome da Empresa:</strong> {{ $contrato->nome_empresa ?? 'Não informado' }}</p>
                        <p><strong>CNPJ:</strong> {{ $contrato->cnpj ?? 'Não informado' }}</p>
                        <p><strong>Telefone:</strong> {{ $contrato->telefone ?? 'Não informado' }}</p>
                        <p><strong>Valor do Contrato:</strong> {{ isset($contrato->valor_contrato) ? 'R$ ' . number_format($contrato->valor_contrato, 2, ',', '.') : 'Não informado' }}</p>
                    </div>
                @endforeach
            @else
                <p>Nenhum contrato associado a este processo.</p>
            @endif
            <hr>
        @endforeach
    @else
        <p>Nenhum resultado encontrado para os filtros selecionados.</p>
    @endif
</body>
</html>