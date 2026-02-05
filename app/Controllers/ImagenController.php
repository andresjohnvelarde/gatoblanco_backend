<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ImagenController extends ResourceController
{
    public function upload()
    {
        $file = $this->request->getFile('imagen');

        if (!$file || !$file->isValid()) {
            return $this->fail('Imagen no válida');
        }

        // Validar con las reglas de CI4 (más seguro)
        $validationRule = [
            'imagen' => [
                'label' => 'Archivo de imagen',
                'rules' => 'uploaded[imagen]|is_image[imagen]|mime_in[imagen,image/jpg,image/jpeg,image/png,image/webp]',
            ],
        ];

        if (!$this->validate($validationRule)) {
            return $this->fail($this->validator->getErrors());
        }

        $ruta = FCPATH . 'uploads/noticias/';
        if (!is_dir($ruta)) {
            mkdir($ruta, 0755, true);
        }

        $nombre = $file->getRandomName(); // CI4 genera un nombre seguro
        $nombreWebp = pathinfo($nombre, PATHINFO_FILENAME) . '.webp';
        $destino = $ruta . $nombreWebp;

        // 🚀 USAR LA LIBRERÍA DE CI4 PARA CONVERTIR
        // Esto detecta el formato original automáticamente (PNG, JPG, etc)
        \Config\Services::image()
            ->withFile($file->getTempName())
            ->convert(IMAGETYPE_WEBP) // Convertir a WebP
            ->save($destino, 80);      // Calidad 80

        return $this->respondCreated([
            // Solo concatenamos la carpeta y el nombre del archivo
            'url' => 'uploads/noticias/' . $nombreWebp
        ]);
    }

    public function deleteByUrl()
    {
        $data = $this->request->getJSON(true);

        if (empty($data['url'])) {
            return $this->fail('URL requerida');
        }

        $path = parse_url($data['url'], PHP_URL_PATH);
        $archivo = basename($path);

        $ruta = FCPATH . 'uploads/noticias/' . $archivo;

        if (!file_exists($ruta)) {
            return $this->failNotFound('Imagen no encontrada');
        }

        unlink($ruta);

        return $this->respond([
            'message' => 'Imagen eliminada correctamente'
        ]);
    }
}
