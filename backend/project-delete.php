<?php
    use Backend\MYAPI\Delete as Delete;

    require_once __DIR__.'/MYAPI/Delete.php';

    $proyectos = new Delete('proyecto_db');
    $proyectos->delete( $_POST['id'] );
    echo $proyectos->getData();
?>