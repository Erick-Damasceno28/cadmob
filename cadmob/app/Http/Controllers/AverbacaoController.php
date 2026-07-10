<?php

namespace App\Http\Controllers;

use App\Models\Averbacao;
use App\Models\Imovel;
use Illuminate\Http\Request;

class AverbacaoController extends Controller
{
    public function store(Request $request, Imovel $imovel)
    {
        $request->validate([
            'evento'   => 'required|in:aumento_area,reducao_area,observacao,cancelamento,reativacao',
            'medida'   => 'nullable|numeric|min:0.01',
            'descricao'=> 'nullable|string|max:1000',
        ]);

        // Validações específicas por evento
        if (in_array($request->evento, ['aumento_area', 'reducao_area'])) {
            $request->validate([
                'medida' => 'required|numeric|min:0.01',
            ]);
        }

        // Cancelamento — imóvel deve estar ativo
        if ($request->evento === 'cancelamento') {
            if ($imovel->situacao === 'inativo') {
                return back()->withErrors(['evento' => 'O imóvel já está inativo.']);
            }
            $imovel->update(['situacao' => 'inativo']);
        }

        // Reativação — imóvel deve estar inativo
        if ($request->evento === 'reativacao') {
            if ($imovel->situacao === 'ativo') {
                return back()->withErrors(['evento' => 'O imóvel já está ativo.']);
            }
            $imovel->update(['situacao' => 'ativo']);
        }

        $imovel->averbacoes()->create([
            'evento'    => $request->evento,
            'medida'    => $request->medida,
            'descricao' => $request->descricao,
            'data'      => now()->toDateString(),
        ]);

        return back()->with('success', 'Averbação registrada com sucesso!');
    }
}
