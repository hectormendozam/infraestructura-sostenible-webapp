<?php
    use Backend\MYAPI\Read as Read;

    require_once __DIR__.'/MYAPI/Read.php';

    $proyectos = new Read('proyecto_db');
    $proyectos->single( $_POST['id'] );
    echo $proyectos->getData();
?>