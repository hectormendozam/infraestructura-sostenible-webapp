<?php
namespace Backend\MYAPI;

use Backend\MYAPI\Database;
require_once __DIR__ .'/Database.php';

class Create extends Database {

    public function __construct($db, $user='root', $pass='12345678') {
        parent::__construct($user,$pass, $db);
    }

    public function add($jsonOBJ) {
        $this->data = array(
            'status'  => 'error',
            'message' => 'Ya existe un proyecto con ese nombre'
        );

        if(isset($jsonOBJ->nombre)) {
            // SE ASUME QUE LOS DATOS YA FUERON VALIDADOS ANTES DE ENVIARSE AND eliminado = 0
            $sql = "SELECT * FROM proyectos WHERE nombre = '{$jsonOBJ->nombre}' AND eliminado = 0";
            $result = $this->conexion->query($sql);
        }

        if (isset($jsonOBJ->nombre, $jsonOBJ->descripcion)) {
            $stmt = $this->conexion->prepare("INSERT INTO proyectos (nombre, descripcion, usuario_id, eliminado) VALUES (?, ?, ?, 0)");
            $stmt->bind_param("ssi", $jsonOBJ->nombre, $jsonOBJ->descripcion, $jsonOBJ->user_id);
            if ($stmt->execute()) {
                $this->data['status'] = "success";
                $this->data['message'] = "El proyecto se ha agregado correctamente";
            } else {
                $this->data['message'] = "Error al agregar el proyecto: " . $this->conexion->error;
            }
            $stmt->close();
            } else {
                $this->data['message'] = "Faltan datos obligatorios.";
            }
    
        
    }
}

?>