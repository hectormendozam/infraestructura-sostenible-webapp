<?php
    use Backend\MYAPI\Update as Update;

    require_once __DIR__.'/MYAPI/Update.php';

    $proyectos = new Update('proyecto_db');
    $entrada = file_get_contents('php://input');
    $jsonOBJ = json_decode($entrada);
    $proyectos->edit( $jsonOBJ );
    echo $proyectos->getData();


?>