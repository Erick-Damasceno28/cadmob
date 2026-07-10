<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Imóveis</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        thead th {
            background-color: #4169E1;
            color: white;
            padding: 7px 6px;
            text-align: left;
            font-size: 10px;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tbody td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        .badge-ativo {
            color: #16a34a;
            font-weight: bold;
        }
        .badge-inativo {
            color: #dc2626;
            font-weight: bold;
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
        <br>
        <h1>RELATÓRIO DE IMÓVEIS</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th>Inscrição</th>
                <th>Contribuinte</th>
                <th>Tipo</th>
                <th>Logradouro</th>
                <th>Número</th>
                <th>Bairro</th>
                <th>Área Terreno</th>
                <th>Área Edificação</th>
                <th>Situação</th>
            </tr>
        </thead>
        <tbody>
            @forelse($imoveis as $imovel)
            <tr>
                <td>{{ $imovel->id }}</td>
                <td>{{ $imovel->pessoa?->nome ?? '-' }}</td>
                <td>{{ ucfirst($imovel->tipo) }}</td>
                <td>{{ $imovel->endereco }}</td>
                <td>{{ $imovel->numero }}</td>
                <td>{{ $imovel->bairro }}</td>
                <td>{{ $imovel->area_terreno ? $imovel->area_terreno . ' m²' : '-' }}</td>
                <td>
                    @php
                        $areaTotal = $imovel->area_edificacao ?? 0;
                        foreach($imovel->averbacoes as $av) {
                            if($av->evento === 'aumento_area') $areaTotal += $av->medida;
                            if($av->evento === 'reducao_area') $areaTotal -= $av->medida;
                        }
                    @endphp
                    {{ $areaTotal > 0 ? $areaTotal . ' m²' : '-' }}
                </td>
                <td>
                    <span class="{{ $imovel->situacao === 'ativo' ? 'badge-ativo' : 'badge-inativo' }}">
                        {{ ucfirst($imovel->situacao) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center;">Nenhum imóvel cadastrado.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Gerado em {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
