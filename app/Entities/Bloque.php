<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class BloqueEntity extends Entity
{
    protected $attributes = [
        'idbloque'      => null,
        'idpublicacion' => null,
        'tipo'          => null,
        'url'           => null,
        'texto'         => null,
        'alineacion'         => null,
        'orden'         => null,
        'created_at'    => null,
        'updated_at'    => null,
    ];

    protected $casts = [
        'idbloque'      => 'integer',
        'idpublicacion' => 'integer',
        'orden'         => 'integer',
    ];
}
