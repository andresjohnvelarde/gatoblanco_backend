<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Usuario;

class UsuarioModel extends Model
{
    protected $table      = 'usuario';
    protected $primaryKey = 'idusuario';

    protected $returnType = Usuario::class;

    protected $allowedFields = [
        'username',
        'nombres',
        'apellidos',
        'password',
        'rol'
    ];
}
