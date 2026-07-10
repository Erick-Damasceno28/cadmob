<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Imovel extends Model
{
    use HasFactory;

    protected $table = 'imoveis';

    protected $fillable = [
        'pessoa_id',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'tipo',
        'area_terreno',
        'area_edificacao',
        'situacao',
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class);
    }

    public function averbacoes()
{
    return $this->hasMany(Averbacao::class);
}

}
