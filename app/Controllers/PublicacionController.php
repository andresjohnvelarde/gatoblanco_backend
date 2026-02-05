<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\PublicacionModel;
use App\Models\BloqueModel;
use App\Models\AutoriaModel;

class PublicacionController extends ResourceController
{
    protected $modelName = PublicacionModel::class;
    protected $format    = 'json';

    // 📌 Listar (por tipo opcional)
    public function indexPublico()
    {
        $tipo = $this->request->getGet('tipo');

        $query = $this->model
            ->where('estado', 1); // 👈 solo visibles

        if ($tipo) {
            $query->where('tipo', $tipo);
        }

        $registros = $query->orderBy('fecha_publicacion', 'DESC')->findAll();

        // Formatear fecha_publicacion a "DD de Mes, AAAA"
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        foreach ($registros as $registro) {
            $fecha = $registro->fecha_publicacion; // 👈 propiedad del objeto
            if ($fecha) {
                [$anio, $mes, $dia] = explode('-', $fecha);
                $registro->fecha_publicacion_formateada = intval($dia) . ' de ' . $meses[intval($mes)] . ', ' . $anio;
            } else {
                $registro->fecha_publicacion_formateada = '';
            }
        }

        return $this->respond($registros);
    }

    // 📌 Listar los 6 registros más recientes (noticias o reportajes) con fecha formateada
    public function indexRecientesPublico()
    {
        $query = $this->model
            ->where('estado', 1) // solo visibles
            ->orderBy('fecha_publicacion', 'DESC')
            ->limit(6);

        $registros = $query->findAll();

        // Formatear fecha_publicacion a "DD de Mes, AAAA"
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        foreach ($registros as $registro) {
            $fecha = $registro->fecha_publicacion; // 👈 propiedad del objeto
            if ($fecha) {
                [$anio, $mes, $dia] = explode('-', $fecha);
                $registro->fecha_publicacion_formateada = intval($dia) . ' de ' . $meses[intval($mes)] . ', ' . $anio;
            } else {
                $registro->fecha_publicacion_formateada = '';
            }
        }

        return $this->respond($registros);
    }

    public function indexMasVistosPublico()
    {
        $query = $this->model
            ->where('estado', 1) // solo visibles
            ->orderBy('visualizaciones', 'DESC') // ordenar por más vistas
            ->limit(6);


        $registros = $query->findAll();


        // Formatear fecha_publicacion a "DD de Mes, AAAA"
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];


        foreach ($registros as $registro) {
            $fecha = $registro->fecha_publicacion; // 👈 propiedad del objeto
            if ($fecha) {
                [$anio, $mes, $dia] = explode('-', $fecha);
                $registro->fecha_publicacion_formateada = intval($dia) . ' de ' . $meses[intval($mes)] . ', ' . $anio;
            } else {
                $registro->fecha_publicacion_formateada = '';
            }
        }


        return $this->respond($registros);
    }

    // 📌 Obtener una publicación por ID
    public function indexPublicacionPublico($id)
    {
        // Buscar la publicación visible por ID
        $registro = $this->model
            ->where('estado', 1) // solo visibles
            ->find($id);

        if (!$registro) {
            return $this->failNotFound('Publicación no encontrada');
        }

        // Formatear fecha_publicacion a "DD de Mes, AAAA"
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        $fecha = $registro->fecha_publicacion;
        if ($fecha) {
            [$anio, $mes, $dia] = explode('-', $fecha);
            $registro->fecha_publicacion_formateada = intval($dia) . ' de ' . $meses[intval($mes)] . ', ' . $anio;
        } else {
            $registro->fecha_publicacion_formateada = '';
        }

        $db = \Config\Database::connect();

        $bloques = $db->table('bloque')
            ->where('idpublicacion', $id)
            ->orderBy('orden', 'ASC')
            ->get()
            ->getResultArray();

        $autorias = $db->table('autoria')
            ->where('idpublicacion', $id)
            ->orderBy('orden', 'ASC')
            ->get()
            ->getResultArray();

        return $this->respond([
            ...$registro->toArray(),
            'bloques' => $bloques,
            'autorias' => $autorias
        ]);

        // return $this->respond($registro);
    }

    public function indexAdmin()
    {
        $tipo = $this->request->getGet('tipo');
        $query = $this->model;

        if ($tipo) {
            $query->where('tipo', $tipo);
        }

        $publicaciones = $query->orderBy('fecha_publicacion', 'DESC')->findAll();

        $db = \Config\Database::connect();
        $resultado = [];

        // Diccionario de meses
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        foreach ($publicaciones as $pub) {
            $pubData = $pub->toArray();

            // --- LÓGICA DE FECHA FORMATEADA ---
            $fecha = $pub->fecha_publicacion;
            if ($fecha) {
                // Usamos explode suponiendo formato YYYY-MM-DD
                $partes = explode('-', $fecha);
                if (count($partes) === 3) {
                    [$anio, $mes, $dia] = $partes;
                    $pubData['fecha_publicacion_formateada'] = intval($dia) . ' de ' . $meses[intval($mes)] . ', ' . $anio;
                } else {
                    $pubData['fecha_publicacion_formateada'] = $fecha; // fallback por si el formato falla
                }
            } else {
                $pubData['fecha_publicacion_formateada'] = '';
            }
            // ----------------------------------

            // Bloques asociados
            $bloques = $db->table('bloque')
                ->where('idpublicacion', $pub->idpublicacion)
                ->orderBy('orden', 'ASC')
                ->get()
                ->getResultArray();

            $pubData['bloques'] = $bloques;
            $resultado[] = $pubData;
        }

        return $this->respond($resultado);
    }

    //Obtener publicación id para admins
    public function obtenerPublicacionId($id)
    {
        $publicacion = $this->model->find($id);

        if (!$publicacion) {
            return $this->failNotFound('Publicación no encontrada');
        }

        $db = \Config\Database::connect();

        $pubData = $publicacion->toArray();

        // --- LÓGICA DE FECHA FORMATEADA ---
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        $fecha = $publicacion->fecha_publicacion;
        if ($fecha) {
            $partes = explode('-', $fecha);
            if (count($partes) === 3) {
                [$anio, $mes, $dia] = $partes;
                $pubData['fecha_publicacion_formateada'] = intval($dia) . ' de ' . $meses[intval($mes)] . ', ' . $anio;
            } else {
                $pubData['fecha_publicacion_formateada'] = $fecha;
            }
        } else {
            $pubData['fecha_publicacion_formateada'] = '';
        }

        $bloques = $db->table('bloque')
            ->where('idpublicacion', $id)
            ->orderBy('orden', 'ASC')
            ->get()
            ->getResultArray();

        $autorias = $db->table('autoria')
            ->where('idpublicacion', $id)
            ->orderBy('orden', 'ASC')
            ->get()
            ->getResultArray();

        $pubData['bloques'] = $bloques;
        $pubData['autorias'] = $autorias;

        return $this->respond($pubData);
    }


    // 📌 Crear publicación


    public function create()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $data = (array) $this->request->getJSON(true);

        if (empty($data)) {
            return $this->fail('No se recibieron datos válidos');
        }

        // 🔹 Extraer bloques y quitarlos del payload principal
        $bloques = $data['bloques'] ?? [];
        unset($data['bloques']);

        // 🔹 Extraer bloques y quitarlos del payload principal
        $autorias = $data['autorias'] ?? [];
        unset($data['autorias']);

        // 🔹 Solo dejamos Twitter como opcional
        if (!isset($data['link_twitter']) || $data['link_twitter'] === '') {
            $data['link_twitter'] = null;
        }

        // 🔹 Crear publicación
        if (!$this->model->insert($data)) {
            return $this->fail($this->model->errors());
        }

        $idPublicacion = $this->model->getInsertID();

        // 🔹 Crear bloques
        $bloqueModel = new BloqueModel();

        foreach ($bloques as $bloque) {
            $bloqueData = [
                'idpublicacion' => $idPublicacion,
                'tipo'          => $bloque['tipo'],
                'texto'         => $bloque['texto'] ?? null,
                'alineacion'         => $bloque['alineacion'] ?? null,
                'url'           => $bloque['url'] ?? null,
                'orden'         => $bloque['orden'],
            ];

            if (!$bloqueModel->insert($bloqueData)) {
                $db->transRollback();
                return $this->fail($bloqueModel->errors());
            }
        }

        // 🔹 Crear autorías
        $autoriaModel = new AutoriaModel();

        foreach ($autorias as $autoria) {
            $autoriaData = [
                'idpublicacion' => $idPublicacion,
                'texto'         => $autoria['texto'] ?? null,
                'url'           => $autoria['url'] ?? null,
                'orden'         => $autoria['orden'],
            ];

            if (!$autoriaModel->insert($autoriaData)) {
                $db->transRollback();
                return $this->fail($autoriaModel->errors());
            }
        }

        $db->transComplete();

        return $this->respondCreated([
            'message' => 'Publicación creada correctamente',
            'id'      => $idPublicacion
        ]);
    }

    // 📌 Actualizar
    public function update($id = null)
    {
        $old_data = $this->model->find($id);

        // Si $old_data es null, significa que no existe
        if (!$old_data) {
            return $this->failNotFound('Publicación no encontrada');
        }

        $data = $this->request->getJSON(true);

        if (empty($data)) {
            return $this->fail('No hay datos para actualizar');
        }

        if ($old_data->img1 != $data['img1']) {
            $this->eliminarArchivoBloque('imagen', $old_data->img1);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 🔹 1. Actualizar datos base de publicación
        $publicacionData = [
            'titulo'           => $data['titulo'] ?? null,
            'descripcion'      => $data['descripcion'] ?? null,
            'fecha_publicacion' => $data['fecha_publicacion'] ?? null,
            'estado'           => $data['estado'] ?? null,
            'link_twitter'     => $data['link_twitter'] ?? null,
            'img1'     => $data['img1'] ?? null,
        ];

        $this->model->update($id, array_filter($publicacionData, fn($v) => $v !== null));

        // 🔹 2. Manejo de bloques
        $bloqueModel = new \App\Models\BloqueModel();

        $bloquesActuales = $db->table('bloque')
            ->where('idpublicacion', $id)
            ->get()
            ->getResultArray();

        $bloquesRecibidos = $data['bloques'] ?? [];

        $idsRecibidos = array_filter(array_column($bloquesRecibidos, 'idbloque'));
        log_message('debug', 'IDs recibidos: ' . json_encode($idsRecibidos));

        // 🔥 BLOQUES ELIMINADOS
        foreach ($bloquesActuales as $bloque) {
            if (!in_array($bloque['idbloque'], $idsRecibidos)) {

                if (in_array($bloque['tipo'], ['imagen', 'video'])) {
                    log_message('debug', 'DE COMO LLEGAMOS ACÁ');
                    $this->eliminarArchivoBloque($bloque['tipo'], $bloque['url']);
                }

                $bloqueModel->delete($bloque['idbloque']);
            }
        }

        // 🔄 INSERTAR / ACTUALIZAR
        foreach ($bloquesRecibidos as $bloque) {

            $payload = [
                'idpublicacion' => $id,
                'tipo'          => $bloque['tipo'],
                'texto'         => $bloque['texto'] ?? null,
                'alineacion'         => $bloque['alineacion'] ?? null,
                'url'           => $bloque['url'] ?? null,
                'orden'         => $bloque['orden']
            ];

            // 🆕 NUEVO
            if (empty($bloque['idbloque'])) {
                $bloqueModel->insert($payload);
                continue;
            }

            // 🔄 EXISTENTE
            $bloqueDB = array_filter(
                $bloquesActuales,
                fn($b) => $b['idbloque'] == $bloque['idbloque']
            );
            $bloqueDB = array_values($bloqueDB)[0] ?? null;

            // Si cambia archivo → eliminar anterior
            if (
                $bloqueDB &&
                in_array($bloque['tipo'], ['imagen', 'video']) &&
                $bloqueDB['url'] !== $bloque['url']
            ) {
                $this->eliminarArchivoBloque($bloque['tipo'], $bloqueDB['url']);
            }

            $bloqueModel->update($bloque['idbloque'], $payload);
        }

        // 🔹 2. Manejo de autorias
        $autoriaModel = new \App\Models\AutoriaModel();

        $autoriasActuales = $db->table('autoria')
            ->where('idpublicacion', $id)
            ->get()
            ->getResultArray();

        $autoriasRecibidos = $data['autorias'] ?? [];

        $idsRecibidosAutorias = array_filter(array_column($autoriasRecibidos, 'idautoria'));
        log_message('debug', 'IDs recibidos: ' . json_encode($idsRecibidosAutorias));

        // 🔥 AUTORÍAS ELIMINADAS
        foreach ($autoriasActuales as $autoria) {
            if (!in_array($autoria['idautoria'], $idsRecibidosAutorias)) {

                log_message('debug', 'no hay esto?');
                $this->eliminarArchivoBloque('imagen', $autoria['url']);

                $autoriaModel->delete($autoria['idautoria']);
            }
        }

        // 🔄 INSERTAR / ACTUALIZAR
        foreach ($autoriasRecibidos as $autoria) {

            $payload = [
                'idpublicacion' => $id,
                'texto'         => $autoria['texto'] ?? null,
                'url'           => $autoria['url'] ?? null,
                'orden'         => $autoria['orden']
            ];

            // 🆕 NUEVO
            if (empty($autoria['idautoria'])) {
                $autoriaModel->insert($payload);
                continue;
            }

            // 🔄 EXISTENTE
            $autoriaDB = array_filter(
                $autoriasActuales,
                fn($b) => $b['idautoria'] == $autoria['idautoria']
            );
            $autoriaDB = array_values($autoriaDB)[0] ?? null;

            // Si cambia archivo → eliminar anterior
            if (
                $autoriaDB &&
                $autoriaDB['url'] !== $autoria['url']
            ) {
                $this->eliminarArchivoBloque('imagen', $autoriaDB['url']);
            }

            $autoriaModel->update($autoria['idautoria'], $payload);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->fail('Error al actualizar la publicación');
        }

        return $this->respondUpdated([
            'message' => 'Publicación actualizada correctamente'
        ]);
    }

    private function eliminarArchivoBloque(string $tipo, string $valor)
    {
        if (!$valor) return;

        if ($tipo === 'imagen') {
            // BD guarda: uploads/noticias/archivo.webp
            $ruta = FCPATH . $valor;
        }

        if ($tipo === 'video') {
            // BD guarda solo: archivo.mp4
            $ruta = FCPATH . 'uploads/videos/' . $valor;
        }

        if (isset($ruta) && file_exists($ruta)) {
            unlink($ruta);
        }
    }

    public function delete($id = null)
    {
        $old_data = $this->model->find($id);

        // Si $old_data es null, significa que no existe
        if (!$old_data) {
            return $this->failNotFound('Publicación no encontrada');
        }


        $db = \Config\Database::connect();
        $db->transStart();

        $bloqueModel = new \App\Models\BloqueModel();

        $bloques = $bloqueModel
            ->asArray()
            ->where('idpublicacion', $id)
            ->findAll();


        foreach ($bloques as $bloque) {
            if (in_array($bloque['tipo'], ['imagen', 'video'])) {
                $this->eliminarArchivoBloque($bloque['tipo'], $bloque['url']);
            }
            $bloqueModel->delete($bloque['idbloque']);
        }


        $autoriaModel = new \App\Models\AutoriaModel();

        $autorias = $autoriaModel
            ->asArray()
            ->where('idpublicacion', $id)
            ->findAll();

        foreach ($autorias as $autoria) {
            $this->eliminarArchivoBloque('imagen', $autoria['url']);
            $autoriaModel->delete($autoria['idautoria']);
        }

        $this->eliminarArchivoBloque('imagen', $old_data->img1);

        $this->model->delete($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->fail('Error al eliminar la publicación');
        }

        return $this->respondDeleted([
            'message' => 'Publicación eliminada correctamente'
        ]);
    }

    // 👀 Incrementar visualizaciones
    public function incrementarVisualizacion($id)
    {
        $publicacion = $this->model->find($id);

        if (!$publicacion) {
            return $this->failNotFound();
        }

        $this->model->update($id, [
            'visualizaciones' => $publicacion->visualizaciones + 1
        ]);

        return $this->respond(['ok' => true]);
    }

    // 👀 Incrementar visualizaciones
    public function cambiarEstado($id)
    {
        $publicacion = $this->model->find($id);

        if (!$publicacion) {
            return $this->failNotFound();
        }

        $this->model->update($id, [
            'estado' => $publicacion->estado * -1
        ]);

        return $this->respond(['ok' => true]);
    }
}
