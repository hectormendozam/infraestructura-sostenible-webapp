<?php
    use ECOTRACK\MYAPI\Update;
    require_once __DIR__.'/vendor/autoload.php';

    $proyectos = new Update('proyecto_db');
    $proyectos->edit( json_decode( json_encode($_POST) ) );
    echo $proyectos->getData();
?>