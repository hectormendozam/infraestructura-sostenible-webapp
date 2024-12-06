<?php
    use Backend\MYAPI\Read as Read;

    require_once __DIR__.'/MYAPI/Read.php';

    $productos = new Read('proyecto_db');
    $productos->single( $_POST['id'] );
    echo $productos->getData();
?>