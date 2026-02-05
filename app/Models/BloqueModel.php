<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\BloqueEntity;

class BloqueModel extends Model
{
    protected $table            = 'bloque';
    protected $primaryKey       = 'idbloque';
    protected $returnType       = BloqueEntity::class;
    // protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'idpublicacion',
        'tipo',
        'url',
        'texto',
        'alineacion',
        'orden',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'idpublicacion' => 'required|is_natural_no_zero',
        'tipo'          => 'required|in_list[subtitulo,parrafo,imagen,video]',
        'orden'         => 'required|integer',
    ];

    protected $validationMessages = [
        'tipo' => [
            'in_list' => 'Tipo de bloque no válido',
        ],
    ];

    protected $skipValidation = false;
}
