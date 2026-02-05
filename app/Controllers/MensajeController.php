<?php

namespace App\Controllers;

use App\Models\MensajeModel;
use CodeIgniter\RESTful\ResourceController;

class MensajeController extends ResourceController
{
    protected $modelName = MensajeModel::class;
    protected $format    = 'json';

    // 🔹 Obtener todos los mensajes
    public function index()
    {
        return $this->respond(
            $this->model->orderBy('created_at', 'DESC')->findAll()
        );
    }

    // 🔹 Crear mensaje
    public function create()
    {
        $data = $this->request->getJSON(true);

        $rules = [
            'nombre'  => 'required|min_length[3]',
            'celular' => 'required|min_length[6]',
            'asunto'  => 'required|min_length[3]',
            'mensaje' => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $id = $this->model->insert($data);

        if (!$id) {
            return $this->fail('No se pudo guardar el mensaje');
        }

        return $this->respondCreated([
            'message' => 'Mensaje enviado correctamente'
        ]);
    }

    // 🔹 Eliminar mensaje
    public function delete($id = null)
    {
        if (!$this->model->find($id)) {
            return $this->failNotFound('Mensaje no encontrado');
        }

        $this->model->delete($id);

        return $this->respondDeleted([
            'message' => 'Mensaje eliminado correctamente'
        ]);
    }
}
