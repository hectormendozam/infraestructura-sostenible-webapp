<?php
    use Backend\MYAPI\Read as Read;

    require_once __DIR__.'/MYAPI/Read.php';

    // Obtener el cuerpo de la solicitud
    $inputData = file_get_contents('php://input');

    // Decodificar el JSON
    $data = json_decode($inputData, true); // El segundo parámetro lo convierte en un array asociativo
    // Verificar si se recibió el ID
    if (isset($data['id'])) {
        $id = $data['id']; // Asignar el valor de 'id' a la variable $id
        $proyectos = new Read('proyecto_db');
    
        $proyectos->single($id);
        echo $proyectos->getData();
    } else {
    // Si no se recibe 'id', devolver un mensaje de error
    echo json_encode([
        'status' => 'error',
        'message' => 'Falta el ID del proyecto.'
    ]);
    }
?>