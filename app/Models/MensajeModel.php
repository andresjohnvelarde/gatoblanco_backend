<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Mensaje;

class MensajeModel extends Model
{
    protected $table      = 'mensaje';
    protected $primaryKey = 'idmensaje';

    protected $returnType = Mensaje::class;
    protected $useTimestamps = false;

    protected $allowedFields = [
        'nombre',
        'celular',
        'asunto',
        'mensaje'
    ];
}
