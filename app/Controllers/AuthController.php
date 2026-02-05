<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class AuthController extends ResourceController
{
    public function login()
    {
        $data = $this->request->getJSON(true);
        if (!isset($data['username']) || !isset($data['password'])) {
            return $this->fail('Los campos username y password son requeridos.', 400);
        }

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->where('username', $data['username'])->first();

        if (!$usuario) {
            return $this->fail('Error de credenciales.', 401);
        }

        // if (!password_verify($data['password'], $usuario['password'])) {
        //     return $this->fail('Error de credenciales.', 401);
        // }

        if (!password_verify($data['password'], $usuario->password)) {
            return $this->fail('Error de credenciales.', 401);
        }

        $token = $this->generateToken($usuario);

        // 🔹 Colocar la cookie HttpOnly aquí
        $response = service('response');

        $response->setCookie([
            'name'     => 'jwt',             // nombre de la cookie
            'value'    => $token,            // valor (JWT)
            'expire'   => 86400,              // duración en segundos
            'path'     => '/',               // path
            'domain'   => '',                // vacío = localhost, no false
            'secure'   => false,             // true solo si HTTPS
            'httponly' => true,              // HttpOnly
            'samesite' => 'Lax',             // Lax, Strict o None
        ]);


        return $this->respond([
            'status' => 200,
            'message' => 'Inicio de sesión exitoso.',
            'data' => [
                'username' => $usuario->username,
                'token'    => $token,
                'rol'      => $usuario->rol,
            ]
        ]);
    }

    public function generateToken($usuario)
    {
        $secretKey = getenv('JWT_SECRET');
        $expirationTime = time() + 86400;
        $payload = [
            'iat' => time(),
            'exp' => $expirationTime,
            'username' => $usuario->username,
            'rol'      => $usuario->rol,
        ];
        $token = JWT::encode($payload, $secretKey, 'HS256');
        return $token;
    }
}
