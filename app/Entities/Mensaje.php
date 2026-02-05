<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Mensaje extends Entity
{
    protected $attributes = [
        'idmensaje' => null,
        'nombre'    => null,
        'celular'   => null,
        'asunto'    => null,
        'mensaje'   => null,
        'created_at' => null,
    ];

    protected $casts = [
        'idmensaje' => 'integer',
    ];
}
