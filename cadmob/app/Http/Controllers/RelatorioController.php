<?php

namespace App\Http\Controllers;

use App\Models\Imovel;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioController extends Controller
{
    // Relatório Sintético — todos os imóveis
    public function sintetico()
    {
        $imoveis = Imovel::with('pessoa', 'averbacoes')->get();

        $pdf = Pdf::loadView('relatorios.sintetico', compact('imoveis'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('relatorio_imoveis.pdf');
    }

    // Relatório Analítico — imóvel individual com averbações
    public function analitico(Imovel $imovel)
    {
        $imovel->load('pessoa', 'averbacoes');

        $pdf = Pdf::loadView('relatorios.analitico', compact('imovel'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("relatorio_imovel_{$imovel->id}.pdf");
    }
}
