<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\AutoriaEntity;

class AutoriaModel extends Model
{
    protected $table            = 'autoria';
    protected $primaryKey       = 'idautoria';
    protected $returnType       = AutoriaEntity::class;
    // protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'idpublicacion',
        'url',
        'texto',
        'orden',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'idpublicacion' => 'required|is_natural_no_zero',
        'orden'         => 'required|integer',
    ];

    protected $skipValidation = false;
}
