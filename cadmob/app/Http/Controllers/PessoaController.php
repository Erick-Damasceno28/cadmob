<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PessoaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pessoa::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('cpf')) {
            $query->where('cpf', 'like', '%' . $request->cpf . '%');
        }

        if ($request->filled('data_nascimento')) {
            $query->whereDate('data_nascimento', $request->data_nascimento);
        }

        if ($request->filled('sexo')) {
            $query->where('sexo', $request->sexo);
        }

        $pessoas = $query->paginate(10)->withQueryString();

        return Inertia::render('Pessoas/Index', [
            'pessoas' => $pessoas,
            'filtros' => $request->only(['nome', 'cpf', 'data_nascimento', 'sexo']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Pessoas/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'            => 'required|string|max:255',
            'cpf'             => 'required|string|max:14|unique:pessoas',
            'data_nascimento' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $idade = Carbon::parse($value)->age;
                    if ($idade < 18) {
                        $fail('O contribuinte deve ter pelo menos 18 anos de idade.');
                    }
                },
            ],
            'sexo'     => 'required|in:M,F',
            'email'    => 'nullable|email|unique:pessoas',
            'telefone' => 'nullable|string|max:20',
        ]);

        $data = $request->all();
        $data['cpf']     = preg_replace('/\D/', '', $request->cpf);
        $data['telefone'] = preg_replace('/\D/', '', $request->telefone ?? '');

        Pessoa::create($data);

        flash()->success('Pessoa cadastrada com sucesso!');

        return redirect()->route('pessoas.index');
    }

    public function edit(Pessoa $pessoa)
    {
        return Inertia::render('Pessoas/Edit', compact('pessoa'));
    }

    public function update(Request $request, Pessoa $pessoa)
    {
        $request->validate([
            'nome'            => 'required|string|max:255',
            'data_nascimento' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $idade = Carbon::parse($value)->age;
                    if ($idade < 18) {
                        $fail('O contribuinte deve ter pelo menos 18 anos de idade.');
                    }
                },
            ],
            'sexo'     => 'required|in:M,F',
            'email'    => 'nullable|email|unique:pessoas,email,' . $pessoa->id,
            'telefone' => 'nullable|string|max:20',
        ]);

        $data = $request->all();
        $data['telefone'] = preg_replace('/\D/', '', $request->telefone ?? '');

        $pessoa->update($data);

        flash()->success('Pessoa atualizada com sucesso!');

        return redirect()->route('pessoas.index');
    }

    public function destroy(Pessoa $pessoa)
    {
        $pessoa->delete();

        flash()->success('Pessoa excluída com sucesso!');

        return redirect()->route('pessoas.index');
    }
}
