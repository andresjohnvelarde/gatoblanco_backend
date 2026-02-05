<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Publicacion;

class PublicacionModel extends Model
{
    protected $table      = 'publicacion';
    protected $primaryKey = 'idpublicacion';

    protected $returnType = Publicacion::class;

    // protected $useSoftDeletes = false;

    protected $allowedFields = [
        'tipo',
        'titulo',
        'descripcion',
        'fecha_publicacion',
        'estado',
        'img1',
        'img2',
        'img3',
        'parrafo2',
        'parrafo3',
        'link_twitter',
        'visualizaciones',
    ];

    protected $useTimestamps = true;
}
