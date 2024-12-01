<?php
    use ECOTRACK\MYAPI\Create;
    require_once __DIR__.'/vendor/autoload.php';

    $proyectos = new Create('proyecto_db');
    $proyectos->add( json_decode( json_encode($_POST) ) );
    echo $proyectos->getData();
?>