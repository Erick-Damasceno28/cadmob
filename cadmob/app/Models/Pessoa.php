<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class Pessoa extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'pessoas';

    protected $fillable = [
        'nome',
        'cpf',
        'email',
        'telefone',
        'data_nascimento',
        'sexo',
    ];

    public function imoveis()
    {
        return $this->hasMany(Imovel::class);
    }
}
