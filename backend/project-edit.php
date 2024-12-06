<?php
    use Backend\MYAPI\Update as Update;

    require_once __DIR__.'/MYAPI/Update.php';
    header('Content-Type: application/json');


    $proyectos = new Update('proyecto_db');
    $entrada = file_get_contents('php://input');
    $jsonOBJ = json_decode($entrada);

    // Verificar si los datos fueron recibidos correctamente
    if ($jsonOBJ === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al decodificar el JSON'
        ]);
        exit();
    }

    $proyectos->edit( $jsonOBJ );
    echo $proyectos->getData();


?>