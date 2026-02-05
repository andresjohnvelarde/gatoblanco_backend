<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class VideoController extends Controller
{
    public function upload()
    {
        // 1. Validaciones de seguridad y formato
        $validationRule = [
            'video_file' => [
                'label' => 'Video File',
                'rules' => [
                    'uploaded[video_file]',
                    'mime_in[video_file,video/mp4,video/webm]',
                    'max_size[video_file,102400]', // Límite de 100MB
                ],
            ],
        ];

        if (!$this->validate($validationRule)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // 2. Procesar el archivo
        $video = $this->request->getFile('video_file');

        if ($video->isValid() && !$video->hasMoved()) {

            // Definimos la ruta: public/uploads/videos
            $targetPath = FCPATH . 'uploads/videos/';

            // Generar un nombre seguro y mover
            $newName = $video->getRandomName();
            $video->move($targetPath, $newName);

            // 3. Retornar la URL completa para Angular
            return $this->response->setJSON([
                'status'   => 'success',
                'filename' => $newName,
                'url'      => $newName
            ]);
        }

        return $this->response->setStatusCode(500)->setJSON(['error' => 'No se pudo mover el archivo.']);
    }

    public function stream($filename)
    {
        $filepath = FCPATH . 'uploads/videos/' . $filename;

        if (!file_exists($filepath)) {
            return $this->response->setStatusCode(404);
        }

        $size = filesize($filepath);
        $file = fopen($filepath, 'rb');

        // Headers esenciales para que el navegador permita adelantar
        $this->response->setHeader('Content-Type', 'video/mp4');
        $this->response->setHeader('Accept-Ranges', 'bytes');

        // Manejo de peticiones de rango (Range Requests)
        if ($range = $this->request->header('Range')) {
            list($unit, $range) = explode('=', $range->getValue(), 2);
            if ($unit == 'bytes') {
                list($start, $end) = explode('-', $range, 2);
                $end = ($end === '') ? $size - 1 : (int)$end;
                $start = (int)$start;

                $length = $end - $start + 1;
                fseek($file, $start);

                return $this->response
                    ->setStatusCode(206) // Partial Content
                    ->setHeader('Content-Range', "bytes $start-$end/$size")
                    ->setHeader('Content-Length', (string)$length)
                    ->setBody(fread($file, $length));
            }
        }

        // Si no hay rango, enviamos el archivo normal pero con Accept-Ranges
        $this->response->setHeader('Content-Length', (string)$size);
        return $this->response->setBody(fread($file, $size));
    }
}
