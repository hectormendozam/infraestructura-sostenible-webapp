<?php
namespace Backend\MYAPI;

use Backend\MYAPI\Database;
require_once __DIR__ .'/Database.php';

class Delete extends Database {

    public function __construct($db, $user='root', $pass='Capitan23') {
        parent::__construct($user,$pass, $db);
    }

    public function delete($id) {
        // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
        $this->data = array(
            'status'  => 'error',
            'message' => 'La consulta falló'
        );
        // SE VERIFICA HABER RECIBIDO EL ID
        if (isset($id) && is_numeric($id)) {
            // SANITIZAMOS EL ID PARA EVITAR INYECCIÓN SQL
            $id = $this->conexion->real_escape_string($id);

            // SE REALIZA LA QUERY DE BÚSQUEDA Y AL MISMO TIEMPO SE VALIDA SI HUBO RESULTADOS
            $sql = "UPDATE proyectos SET eliminado = 1 WHERE id = {$id}";
            if ( $this->conexion->query($sql) ) {
                $this->data['status'] =  "success";
                $this->data['message'] =  "Proyecto eliminado";
            } else {
                $this->data['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($this->conexion);
            }
            $this->conexion->close();
        } 
        return json_encode($this->data);
    }
}	
?>