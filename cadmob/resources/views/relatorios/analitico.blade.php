<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório Detalhado - Imóvel {{ $imovel->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0;
            color: #1a1a1a;
        }
        .header p {
            font-size: 11px;
            color: #666;
            margin: 4px 0 0;
        }
        .titulo-relatorio {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 16px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .tabela-dados td {
            padding: 8px;
            border: 1px solid #ddd;
            width: 50%;
        }
        .tabela-dados td strong {
            color: #1a1a1a;
        }
        .tabela-averbacoes thead th {
            background-color: #4169E1;
            color: white;
            padding: 7px 6px;
            text-align: left;
            font-size: 10px;
        }
        .tabela-averbacoes tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .tabela-averbacoes tbody td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 20px 0 8px;
            color: #4169E1;
            border-bottom: 2px solid #4169E1;
            padding-bottom: 4px;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PREFEITURA MUNICIPAL DE SÃO LEOPOLDO</h1>
        <p>Estado do Rio Grande do Sul</p>
    </div>

    <div class="titulo-relatorio">RELATÓRIO DETALHADO DE IMÓVEL</div>

    <table class="tabela-dados">
        <tr>
            <td><strong>Inscrição:</strong> {{ $imovel->id }}</td>
            <td><strong>Situação:</strong> {{ ucfirst($imovel->situacao) }}</td>
        </tr>
        <tr>
            <td><strong>Contribuinte:</strong> {{ $imovel->pessoa?->nome ?? '-' }}</td>
            <td><strong>Tipo:</strong> {{ ucfirst($imovel->tipo) }}</td>
        </tr>
        <tr>
            <td><strong>Bairro:</strong> {{ $imovel->bairro }}</td>
            <td><strong>Logradouro:</strong> {{ $imovel->endereco }}</td>
        </tr>
        <tr>
            <td><strong>Número:</strong> {{ $imovel->numero }}</td>
            <td><strong>Complemento:</strong> {{ $imovel->complemento ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Área do Terreno:</strong> {{ $imovel->area_terreno ? $imovel->area_terreno . ' m²' : '-' }}</td>
            <td>
                <strong>Área da Edificação:</strong>
                @php
                    $areaTotal = $imovel->area_edificacao ?? 0;
                    foreach($imovel->averbacoes as $av) {
                        if($av->evento === 'aumento_area') $areaTotal += $av->medida;
                        if($av->evento === 'reducao_area') $areaTotal -= $av->medida;
                    }
                @endphp
                {{ $areaTotal > 0 ? $areaTotal . ' m²' : '-' }}
            </td>
        </tr>
    </table>

    <div class="section-title">AVERBAÇÕES</div>

    <table class="tabela-averbacoes">
        <thead>
            <tr>
                <th>Data</th>
                <th>Evento</th>
                <th>Medida</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            @forelse($imovel->averbacoes as $av)
            <tr>
                <td>{{ \Carbon\Carbon::parse($av->data)->format('d/m/Y') }}</td>
                <td>
                    @php
                        $eventos = [
                            'aumento_area' => 'Aumento Área Construída',
                            'reducao_area' => 'Redução Área Construída',
                            'observacao'   => 'Observação',
                            'cancelamento' => 'Cancelamento',
                            'reativacao'   => 'Reativação',
                        ];
                    @endphp
                    {{ $eventos[$av->evento] ?? $av->evento }}
                </td>
                <td>{{ $av->medida ? $av->medida . ' m²' : '-' }}</td>
                <td>{{ $av->descricao ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">Nenhuma averbação registrada.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Gerado em {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
