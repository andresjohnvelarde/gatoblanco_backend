<?php

namespace App\Controllers;

use App\Models\BloqueModel;
use CodeIgniter\RESTful\ResourceController;

class BloqueController extends ResourceController
{
    protected $modelName = BloqueModel::class;
    protected $format    = 'json';

    // 📌 Crear un bloque individual (opcional, útil para futuro)
    public function create()
    {
        $data = (array) $this->request->getJSON(true);

        if (empty($data)) {
            return $this->fail('Datos inválidos');
        }

        if (!$this->model->insert($data)) {
            return $this->fail($this->model->errors());
        }

        return $this->respondCreated([
            'message' => 'Bloque creado correctamente',
            'id'      => $this->model->getInsertID()
        ]);
    }
}
