<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class JwtAuth implements FilterInterface
{
    public function before(RequestInterface $request, $roles = null)
    {
        if ($request->getMethod() === 'OPTIONS') {
            return;
        }
        // // Obtener el token de la cabecera Authorization
        // $authHeader = $request->getHeaderLine('Authorization');


        // if (!$authHeader) {
        //     return $this->respondWithError('Authorization header not found.', 401);
        // }

        // // Extraer el token del header
        // list($jwt) = sscanf($authHeader, 'Bearer %s');

        $jwt = null;

        // 1️⃣ Revisar header Authorization
        $authHeader = $request->getHeaderLine('Authorization');
        if ($authHeader) {
            list($jwt) = sscanf($authHeader, 'Bearer %s');
        }

        // 2️⃣ Si no está en header, revisar cookie
        if (!$jwt && isset($_COOKIE['jwt'])) {
            $jwt = $_COOKIE['jwt'];
        }

        if (!$jwt) {
            return $this->respondWithError('Token not found.', 401);
        }

        // Verificar el token
        try {
            $key = getenv('JWT_SECRET'); // Asegúrate de definir esto en .env
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            // $allowedRoles = explode(',', $arguments[0] ?? '');
            // print_r($allowedRoles);
            //  print_r($roles);
            //  exit;

            if (!in_array($decoded->rol, $roles)) {
                return $this->respondWithError('Access denied: insufficient permissions.', 403);
            }
            // Comprobar el rol en el token
            // if (!isset($decoded->rol) || $decoded->rol !== 'ROLE_verificador') {
            //     return $this->respondWithError('Insufficient permissions.', 403);
            // }
        } catch (ExpiredException $e) {
            return $this->respondWithError('Token expirado', 401);
        } catch (\Exception $e) {
            return $this->respondWithError('Token is invalid: ' . $e->getMessage(), 401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }

    private function respondWithError($message, $status)
    {
        return service('response')->setJSON(['error' => $message])->setStatusCode($status);
    }
}
