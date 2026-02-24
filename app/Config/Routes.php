<?php
$routes->options('(:any)', function () {
    return service('response')->setStatusCode(204);
});

$routes->post('login', 'AuthController::login', ['filter' => 'cors']);

$routes->group('api', ['filter' => 'cors'], function ($routes) {
    //PÚBLICO
    //Crear mensaje de Contacto
    $routes->post('mensajes', 'MensajeController::create');

    // Ver noticias o reportajes
    $routes->get('publicaciones', 'PublicacionController::indexPublico');

    // Ver noticias y/o reportajes (6 recientes)
    $routes->get('publicaciones/recientes', 'PublicacionController::indexRecientesPublico');

    // Ver noticias y/o reportajes (6 más vistos)
    $routes->get('publicaciones/vistos', 'PublicacionController::indexMasVistosPublico');

    // Ver una publicacion en detalle
    $routes->get('publicaciones/(:num)', 'PublicacionController::indexPublicacionPublico/$1');

    // Aumentar visualizaciones a una noticia o reportaje
    $routes->patch('vista/publicaciones/(:num)', 'PublicacionController::incrementarVisualizacion/$1');


    //ADMINISTRADOR
    //Obtener usuarios
    $routes->get('usuarios', 'UsuarioController::index', [
        'filter' => ['jwt:ROLE_administrador']
    ]);

    //Crear usuario
    $routes->post('usuarios', 'UsuarioController::create', [
        'filter' => ['jwt:ROLE_administrador']
    ]);

    //Obtener usuario por id
    $routes->get('usuarios/(:num)', 'UsuarioController::show/$1', [
        'filter' => ['jwt:ROLE_administrador']
    ]);

    //Editar usuario por id
    $routes->put('usuarios/(:num)', 'UsuarioController::update/$1', [
        'filter' => ['jwt:ROLE_administrador']
    ]);

    //Eliminar usuario por id
    $routes->delete('usuarios/(:num)', 'UsuarioController::delete/$1', [
        'filter' => ['jwt:ROLE_administrador']
    ]);

    //Visualizar mensajes
    $routes->get('mensajes', 'MensajeController::index', [
        'filter' => ['jwt:ROLE_administrador,ROLE_registrador']
    ]);

    //Eliminar mensaje por id
    $routes->delete('mensajes/(:num)', 'MensajeController::delete/$1', [
        'filter' => ['jwt:ROLE_administrador,ROLE_registrador']
    ]);

    //Visualizar noticias y reportajes
    $routes->get('admin/publicaciones', 'PublicacionController::indexAdmin', [
        'filter' => ['jwt:ROLE_administrador,ROLE_registrador']
    ]);

    //Visualizar noticias y reportajes por ID solo administradores/registradores
    $routes->get('admin/publicaciones/(:num)', 'PublicacionController::obtenerPublicacionId/$1', [
        'filter' => ['jwt:ROLE_administrador,ROLE_registrador']
    ]);

    //Registrar noticias y reportajes
    $routes->post('publicaciones', 'PublicacionController::create', [
        'filter' => ['jwt:ROLE_administrador,ROLE_registrador']
    ]);

    //Modificar noticias y reportajes
    $routes->put('publicaciones/(:num)', 'PublicacionController::update/$1', [
        'filter' => ['jwt:ROLE_administrador,ROLE_registrador']
    ]);

    //Cambiar estado de publicación
    $routes->patch('publicaciones/(:num)', 'PublicacionController::cambiarEstado/$1', [
        'filter' => ['jwt:ROLE_administrador,ROLE_registrador']
    ]);

    //Eliminar noticias y reportajes
    $routes->delete('publicaciones/(:num)', 'PublicacionController::delete/$1', [
        'filter' => ['jwt:ROLE_administrador,ROLE_registrador']
    ]);

    //Subir una imagen y devolver url
    $routes->post('imagenes', 'ImagenController::upload', [
        'filter' => ['jwt:ROLE_administrador,ROLE_registrador']
    ]);

    //Eliminar una imagen por su url
    $routes->delete('imagenes', 'ImagenController::deleteByUrl', [
        'filter' => ['jwt:ROLE_administrador,ROLE_registrador']
    ]);

    //Subir un video y devolver url
    $routes->post('videos', 'VideoController::upload', [
        'filter' => ['jwt:ROLE_administrador,ROLE_registrador']
    ]);

    $routes->get('video/stream/(:any)', 'VideoController::stream/$1');

    //Cambiar dimensiones de un bloque tipo imagen
    $routes->patch('bloques/(:num)', 'PublicacionController::cambiarDimensiones/$1', [
        'filter' => ['jwt:ROLE_administrador,ROLE_registrador']
    ]);
});
