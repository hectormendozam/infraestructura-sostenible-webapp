<?php
    use Backend\MYAPI\Create as Create;

    require_once __DIR__.'/MYAPI/Create.php';

    header('Content-Type: application/json');

        $proyectos = new Create('proyecto_db');
        $proyecto = file_get_contents('php://input');
        $jsonOBJ = json_decode($proyecto);
        $proyectos->add($jsonOBJ);
        // Devolver la respuesta como JSON
        echo json_encode($proyectos->getData());
    
?>