<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Entities\Usuario;
use CodeIgniter\RESTful\ResourceController;

class UsuarioController extends ResourceController
{
    protected $modelName = UsuarioModel::class;
    protected $format    = 'json';

    // 🔹 Listar usuarios
    public function index()
    {
        return $this->respond(
            $this->model->findAll()
        );
    }

    // 🔹 Obtener usuario por ID
    public function show($id = null)
    {
        $usuario = $this->model->find($id);

        if (!$usuario) {
            return $this->failNotFound('Usuario no encontrado');
        }

        return $this->respond($usuario);
    }

    // 🔹 Crear usuario
    public function create()
    {
        $data = $this->request->getJSON(true);

        $rules = [
            'username'  => 'required|is_unique[usuario.username]',
            'nombres'   => 'required',
            'apellidos' => 'required',
            'password'  => 'required|min_length[6]'
            // 'rol'       => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $usuario = new Usuario([
            'username'  => $data['username'],
            'nombres'   => $data['nombres'],
            'apellidos' => $data['apellidos'],
            'password'  => password_hash($data['password'], PASSWORD_DEFAULT),
            // 'rol'       => $data['rol']
            'rol'       => 'ROLE_registrador'
        ]);

        $this->model->save($usuario);

        return $this->respondCreated([
            'message' => 'Usuario creado correctamente'
        ]);
    }

    public function update($id = null)
    {
        if (!$this->model->find($id)) {
            return $this->failNotFound('Usuario no encontrado');
        }

        // ✅ LEER JSON CORRECTAMENTE
        $data = $this->request->getJSON(true);

        log_message('debug', json_encode($data));

        $updateData = [];

        if (isset($data['username'])) {
            $updateData['username'] = $data['username'];
        }

        if (isset($data['nombres'])) {
            $updateData['nombres'] = $data['nombres'];
        }

        if (isset($data['apellidos'])) {
            $updateData['apellidos'] = $data['apellidos'];
        }

        if (!empty($data['password'])) {
            $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (empty($updateData)) {
            return $this->fail('No hay datos para actualizar');
        }

        if (!$this->model->update($id, $updateData)) {
            return $this->fail($this->model->errors());
        }

        return $this->respondUpdated([
            'message' => 'Usuario actualizado correctamente'
        ]);
    }


    // 🔹 Eliminar usuario
    public function delete($id = null)
    {
        $usuario = $this->model->find($id);

        if (!$usuario) {
            return $this->failNotFound('Usuario no encontrado');
        }

        $this->model->delete($id);

        return $this->respondDeleted([
            'message' => 'Usuario eliminado correctamente'
        ]);
    }
}
