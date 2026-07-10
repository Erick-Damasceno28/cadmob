<?php

namespace App\Http\Controllers;

use App\Models\Imovel;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ImovelController extends Controller
{
    public function index(Request $request)
    {
        $query = Imovel::with('pessoa');

        if ($request->filled('endereco')) {
            $query->where('endereco', 'like', '%' . $request->endereco . '%');
        }

        if ($request->filled('bairro')) {
            $query->where('bairro', 'like', '%' . $request->bairro . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('situacao')) {
            $query->where('situacao', $request->situacao);
        }

        $imoveis = $query->paginate(10)->withQueryString();

        return Inertia::render('Imoveis/Index', [
            'imoveis' => $imoveis,
            'filtros' => $request->only(['endereco', 'bairro', 'tipo', 'situacao']),
        ]);
    }

    public function create()
    {
        $pessoas = Pessoa::all();
        return Inertia::render('Imoveis/Create', compact('pessoas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pessoa_id'       => 'required|exists:pessoas,id',
            'cep'             => 'nullable|string|max:9',
            'endereco'        => 'required|string|max:255',
            'numero'          => 'required|string|max:10',
            'complemento'     => 'nullable|string|max:255',
            'bairro'          => 'required|string|max:255',
            'cidade'          => 'required|string|max:255',
            'tipo'            => 'required|in:casa,apartamento,terreno',
            'area_terreno'    => 'nullable|numeric|min:0',
            'area_edificacao' => 'nullable|numeric|min:0',
        ]);

        $data = $request->all();
        $data['cep']      = preg_replace('/\D/', '', $request->cep ?? '');
        $data['situacao'] = 'ativo';

        Imovel::create($data);

        flash()->success('Imóvel cadastrado com sucesso!');

        return redirect()->route('imoveis.index');
    }

    public function show(Imovel $imovel)
    {
        $imovel->load('pessoa');
        return Inertia::render('Imoveis/Show', compact('imovel'));
    }

    public function edit(Imovel $imovel)
    {
        $imovel->load('documentos', 'averbacoes');
        $pessoas = Pessoa::all();
        return Inertia::render('Imoveis/Edit', [
            'imovel' => [
                'id'              => $imovel->id,
                'pessoa_id'       => $imovel->pessoa_id,
                'cep'             => $imovel->cep,
                'endereco'        => $imovel->endereco,
                'numero'          => $imovel->numero,
                'complemento'     => $imovel->complemento,
                'bairro'          => $imovel->bairro,
                'cidade'          => $imovel->cidade,
                'tipo'            => $imovel->tipo,
                'area_terreno'    => $imovel->area_terreno,
                'area_edificacao' => $imovel->area_edificacao,
                'situacao'        => $imovel->situacao,
                'documentos'      => $imovel->documentos,
                'averbacoes'      => $imovel->averbacoes,
            ],
            'pessoas' => $pessoas
        ]);
    }

    public function update(Request $request, Imovel $imovel)
    {
        $request->validate([
            'pessoa_id'       => 'required|exists:pessoas,id',
            'cep'             => 'nullable|string|max:9',
            'endereco'        => 'required|string|max:255',
            'numero'          => 'required|string|max:10',
            'complemento'     => 'nullable|string|max:255',
            'bairro'          => 'required|string|max:255',
            'cidade'          => 'required|string|max:255',
            'tipo'            => 'required|in:casa,apartamento,terreno',
            'area_terreno'    => 'nullable|numeric|min:0',
            'area_edificacao' => 'nullable|numeric|min:0',
        ]);

        $data = $request->all();
        $data['cep'] = preg_replace('/\D/', '', $request->cep ?? '');

        $imovel->update($data);

        flash()->success('Imóvel atualizado com sucesso!');

        return redirect()->route('imoveis.index');
    }

    public function destroy(Imovel $imovel)
    {
        $imovel->delete();

        flash()->success('Imóvel excluído com sucesso!');

        return redirect()->route('imoveis.index');
    }
}
