<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::paginate(10);
        return Inertia::render('Usuarios/Index', compact('usuarios'));
    }

    public function create()
    {
        return Inertia::render('Usuarios/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'cpf'      => 'required|string|max:14|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'perfil'   => 'required|in:T,S,A',
        ]);

        User::create([
            'name'     => $request->name,
            'cpf'      => $request->cpf,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'perfil'   => $request->perfil,
            'ativo'    => 'S',
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function edit(User $usuario)
    {
        return Inertia::render('Usuarios/Edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'perfil' => 'required|in:T,S,A',
            'ativo'  => 'required|in:S,N',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name'   => $request->name,
            'perfil' => $request->perfil,
            'ativo'  => $request->ativo,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
    }
}
