<?php
namespace Backend\MYAPI;

use Backend\MYAPI\Database;
require_once __DIR__ . '/Database.php';

class Update extends Database {

    public function __construct($db, $user = 'root', $pass = 'Capitan23') {
        parent::__construct($user, $pass, $db);
    }

    public function edit($jsonOBJ) {
        $this->data = array(
            'status' => 'error',
            'message' => 'La consulta falló'
        );

        if (isset($jsonOBJ->id)) {
            $stmt = $this->conexion->prepare("UPDATE proyectos SET nombre = ?, descripcion = ? WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("ssi", $jsonOBJ->name, $jsonOBJ->descripcion, $jsonOBJ->id);

                if ($stmt->execute()) {
                    $this->data['status'] = "success";
                    $this->data['message'] = "Proyecto actualizado";
                } else {
                    $this->data['message'] = "ERROR: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $this->data['message'] = "ERROR: No se pudo preparar la consulta.";
            }

            $this->conexion->close();
        } else {
            $this->data['message'] = "ERROR: ID del proyecto no proporcionado.";
        }
    }

    public function getData() {
        return json_encode($this->data, JSON_UNESCAPED_UNICODE);
    }
}
