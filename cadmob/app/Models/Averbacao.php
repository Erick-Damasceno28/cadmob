<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Averbacao extends Model
{
    use HasFactory;

    protected $table = 'averbacoes';

    protected $fillable = [
        'imovel_id',
        'evento',
        'medida',
        'descricao',
        'data',
    ];

    public function imovel()
    {
        return $this->belongsTo(Imovel::class);
    }
}
