<?php
namespace Backend\MYAPI;

use Backend\MYAPI\Database;
require_once __DIR__ .'/Database.php';

    error_reporting(E_ALL);
    ini_set('display_errors', 1);


class Create extends Database {

    public function __construct($db, $user='root', $pass='12345678') {
        parent::__construct($user,$pass, $db);
    }

    public function add($jsonOBJ) {
        $this->data = array(
            'status'  => 'error',
            'message' => 'Ya existe un proyecto con ese nombre'
        );

        if(isset($jsonOBJ->name)) {
            // SE ASUME QUE LOS DATOS YA FUERON VALIDADOS ANTES DE ENVIARSE AND eliminado = 0
            $sql = "SELECT * FROM proyectos WHERE nombre = '{$jsonOBJ->name}' AND eliminado = 0";
            $result = $this->conexion->query($sql);

        if ($result->num_rows == 0) {
            $this->conexion->set_charset("utf8");
                $sql = "INSERT INTO proyectos VALUES (null, {$jsonOBJ->name}, '{$jsonOBJ->description}', '{$jsonOBJ->user_id}', 0)";
                if($this->conexion->query($sql)){
                    $this->data['status'] =  "success";
                    $this->data['message'] =  "El proyecto se ha agregado correctamente";
                } else {
                    $this->data['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($this->conexion);
                }
        }
        $result->free();
        $this->conexion->close();
        }
    }
    
}

?>