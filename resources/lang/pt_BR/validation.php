<?php

return [
    'required' => 'O campo :attribute é obrigatório.',
    'string' => 'O campo :attribute deve ser um texto.',
    'unique' => 'Este :attribute já está sendo utilizado.',
    'regex' => [
        'numero_processo' => 'O número do processo deve estar no formato correto (XXXX.XXXXXX/XXXX-XX)',
        'default' => 'O formato do campo :attribute é inválido.'
    ],
    'date' => 'O campo :attribute deve ser uma data válida.',
    'numeric' => 'O campo :attribute deve ser um número.',
    'exists' => 'O :attribute selecionado é inválido.',
    'array' => 'O campo :attribute deve ser um array.',

    'custom' => [
        'numero_processo' => [
            'regex' => 'O número do processo deve estar no formato XXXX.XXXXXX/XXXX-XX'
        ],
        'consumo_despesa.numero_pa' => [
            'regex' => 'O número do PA deve estar no formato XX.XXX.XXX.XXX'
        ],
        'consumo_despesa.natureza_despesa' => [
            'regex' => 'A natureza da despesa deve estar no formato X.X.XX.XX'
        ],
        'permanente_despesa.numero_pa' => [
            'regex' => 'O número do PA deve estar no formato XX.XXX.XXX.XXX'
        ],
        'permanente_despesa.natureza_despesa' => [
            'regex' => 'A natureza da despesa deve estar no formato X.X.XX.XX'
        ],
        'servico_despesa.numero_pa' => [
            'regex' => 'O número do PA deve estar no formato XX.XXX.XXX.XXX'
        ],
        'servico_despesa.natureza_despesa' => [
            'regex' => 'A natureza da despesa deve estar no formato X.X.XX.XX'
        ],
    ],

    'attributes' => [
        'numero_processo' => 'número do processo',
        'consumo_despesa.numero_pa' => 'número do PA (Consumo)',
        'consumo_despesa.natureza_despesa' => 'natureza da despesa (Consumo)',
        'permanente_despesa.numero_pa' => 'número do PA (Permanente)',
        'permanente_despesa.natureza_despesa' => 'natureza da despesa (Permanente)',
        'servico_despesa.numero_pa' => 'número do PA (Serviço)',
        'servico_despesa.natureza_despesa' => 'natureza da despesa (Serviço)',
        'descricao' => 'descrição',
        'requisitante' => 'requisitante',
        'data_entrada' => 'data de entrada',
        'valor_consumo' => 'valor de consumo',
        'valor_permanente' => 'valor permanente',
        'valor_servico' => 'valor de serviço',
        'valor_total' => 'valor total',
        'modalidade' => 'modalidade',
        'procedimentos_auxiliares' => 'procedimentos auxiliares'
    ],
];
