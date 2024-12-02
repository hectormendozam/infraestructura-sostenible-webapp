<?php
    use ECOTRACK\MYAPI\Delete;
    require_once __DIR__.'/vendor/autoload.php';

    $proyectos = new Delete('proyecto_db');
    $proyectos->delete( $_POST['id'] );
    echo $proyectos->getData();
?>