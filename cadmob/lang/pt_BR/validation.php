<?php

return [
    'required' => 'O campo :attribute é obrigatório.',
    'string'   => 'O campo :attribute deve ser uma string.',
    'max'      => [
        'string' => 'O campo :attribute não pode ter mais de :max caracteres.',
    ],
    'min'      => [
        'numeric' => 'O campo :attribute deve ser pelo menos :min.',
    ],
    'email'    => 'O campo :attribute deve ser um endereço de e-mail válido.',
    'unique'   => 'Este :attribute já está cadastrado.',
    'date'     => 'O campo :attribute deve ser uma data válida.',
    'in'       => 'O valor selecionado para :attribute é inválido.',
    'numeric'  => 'O campo :attribute deve ser um número.',

    'attributes' => [
        'nome'            => 'nome',
        'cpf'             => 'CPF',
        'email'           => 'e-mail',
        'telefone'        => 'telefone',
        'data_nascimento' => 'data de nascimento',
        'sexo'            => 'sexo',
        'pessoa_id'       => 'contribuinte',
        'endereco'        => 'logradouro',
        'numero'          => 'número',
        'bairro'          => 'bairro',
        'cidade'          => 'cidade',
        'tipo'            => 'tipo',
        'area_terreno'    => 'área do terreno',
        'area_edificacao' => 'área da edificação',
    ],
];
