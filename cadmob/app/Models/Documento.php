<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';

    protected $fillable = [
        'imovel_id',
        'nome_original',
        'caminho',
        'mime_type',
        'tamanho',
    ];

    public function imovel()
    {
        return $this->belongsTo(Imovel::class);
    }
}
