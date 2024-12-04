<?php
    use Backend\MYAPI\Create as Create;

    require_once __DIR__.'/MYAPI/Create.php';

    $productos = new Create('proyecto_db');
    //$productos = new Create('job_review');
    $post = file_get_contents('php://input');
    $jsonOBJ = json_decode($post);
    $productos->add( $jsonOBJ );
    echo $productos->getData();
?>