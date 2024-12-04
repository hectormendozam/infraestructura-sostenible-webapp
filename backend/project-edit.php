<?php
    use Backend\MYAPI\Update as Update;

    require_once __DIR__.'/MYAPI/Update.php';

    $proyectos = new Update('proyecto_db');
    $proyectos->edit( json_decode( json_encode($_POST) ) );
    echo $proyectos->getData();
?>