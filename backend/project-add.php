<?php
    use Backend\MYAPI\Create as Create;

    require_once __DIR__.'/MYAPI/Create.php';

    header('Content-Type: application/json');

    // Obtener los datos JSON enviados desde el frontend
    $projectData = file_get_contents('php://input');  // Lee los datos JSON
    $jsonOBJ = json_decode($projectData);  // Decodifica los datos JSON a un objeto PHP

    // Verificar si los datos fueron recibidos correctamente
    if ($jsonOBJ === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al decodificar el JSON'
        ]);
        exit();
    }
    
        $proyectos = new Create('proyecto_db');
        $proyectos->add($jsonOBJ);
        // Devolver la respuesta como JSON
        echo json_encode($proyectos->getData());
    
?>