<?php
    use ECOTRACK\MYAPI\Read;
    require_once __DIR__.'/vendor/autoload.php';

    $proyectos = new Read(' proyecto_db');
    $proyectos->search( $_GET['search'] );
    echo $proyectos->getData();
?>