<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Publicacion extends Entity
{
    protected $attributes = [
        'idpublicacion'     => null,
        'tipo'              => null,
        'titulo'            => null,
        'descripcion'       => null,
        'fecha_publicacion' => null,
        'estado'            => 1,
        'img1'              => null,
        'img2'              => null,
        'img3'              => null,
        'parrafo2'          => null,
        'parrafo3'          => null,
        'link_twitter'      => null,
        'visualizaciones'   => 0,
        'created_at'        => null,
        'updated_at'        => null,
        'deleted_at'        => null,
    ];
}