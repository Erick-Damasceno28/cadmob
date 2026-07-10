<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Imovel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function store(Request $request, Imovel $imovel)
    {
        $request->validate([
            'documento' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:3072', // 3MB
            ],
        ]);

        if ($imovel->documentos()->count() >= 5) {
            return back()->withErrors(['documento' => 'O imóvel já possui o máximo de 5 documentos.']);
        }

        $arquivo = $request->file('documento');
        $caminho = $arquivo->store("documentos/{$imovel->id}", 'public');

        $imovel->documentos()->create([
            'nome_original' => $arquivo->getClientOriginalName(),
            'caminho'       => $caminho,
            'mime_type'     => $arquivo->getMimeType(),
            'tamanho'       => $arquivo->getSize(),
        ]);

        return back()->with('success', 'Documento anexado com sucesso!');
    }

    public function download(Documento $documento)
    {
        return Storage::disk('public')->download(
            $documento->caminho,
            $documento->nome_original
        );
    }

    public function destroy(Documento $documento)
    {
        Storage::disk('public')->delete($documento->caminho);
        $documento->delete();

        return back()->with('success', 'Documento excluído com sucesso!');
    }
}
