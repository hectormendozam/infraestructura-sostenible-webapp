<?php
namespace Backend\MYAPI;

use Backend\MYAPI\Database;
require_once __DIR__ .'/Database.php';

    error_reporting(E_ALL);
    ini_set('display_errors', 1);


class Create extends Database {

    public function __construct($db, $user='root', $pass='Capitan23') {
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
            $sql = "INSERT INTO proyectos (nombre, descripcion, usuario_id, eliminado) VALUES (?, ?, ?, 0)";
                $stmt = $this->conexion->prepare($sql);
                $stmt->bind_param("ssi", $jsonOBJ->name, $jsonOBJ->description, $jsonOBJ->user_id); // "ssi" indica: string, string, integer

                if ($stmt->execute()) {
                    $this->data['status'] = "success";
                    $this->data['message'] = "El proyecto se ha agregado correctamente";
                } else {
                    $this->data['message'] = "Error al agregar el proyecto: " . $this->conexion->error;
                }

                $stmt->close();
            } else {
                $this->data['message'] = "Ya existe un proyecto con ese nombre.";
            }
        } else {
            $this->data['message'] = "Faltan datos obligatorios.";
        }

        // Cerrar la conexión
        $this->conexion->close();
    }
}

?>