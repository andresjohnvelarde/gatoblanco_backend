<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Usuario extends Entity
{
    // Atributos que sí quieres exponer
    protected $attributes = [
        'idusuario' => null,
        'username'  => null,
        'nombres'   => null,
        'apellidos' => null,
        'password'  => null,
        'rol'       => null,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null
    ];

    // Ocultar password al convertir a JSON
    protected $hidden = ['password'];
}
