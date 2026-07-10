<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use OwenIt\Auditing\Models\Audit;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::with('user')->latest();

        if ($request->filled('usuario')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->usuario . '%');
            });
        }

        if ($request->filled('evento')) {
            $query->where('event', $request->evento);
        }

        if ($request->filled('data')) {
            $query->whereDate('created_at', $request->data);
        }

        if ($request->filled('tabela')) {
            $query->where('auditable_type', 'like', '%' . $request->tabela . '%');
        }

        $audits = $query->paginate(15)->withQueryString();

        return Inertia::render('Auditoria/Index', [
            'audits'  => $audits,
            'filtros' => $request->only(['usuario', 'evento', 'data', 'tabela']),
        ]);
    }

    public function show(Audit $audit)
    {
        $audit->load('user');
        return Inertia::render('Auditoria/Show', compact('audit'));
    }
}
