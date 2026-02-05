<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class AutoriaEntity extends Entity
{
    protected $attributes = [
        'idautoria'      => null,
        'idpublicacion' => null,
        'url'           => null,
        'texto'         => null,
        'orden'         => null,
        'created_at'    => null,
        'updated_at'    => null,
    ];

    protected $casts = [
        'idautoria'      => 'integer',
        'idpublicacion' => 'integer',
        'orden'         => 'integer',
    ];
}
