<?php
    use ECOTRACK\MYAPI\Read as Read;

    require_once __DIR__.'/API/Read.php';

    $proyectos = new Read('proyecto_db');
    $proyectos->list();
    echo $proyectos->getData();
?>