<?php
namespace Backend\MYAPI;

use Backend\MYAPI\Database;
require_once __DIR__ .'/Database.php';

class Update extends Database {

    public function __construct($db, $user='root', $pass='12345678') {
        parent::__construct($user,$pass, $db);
    }

    public function edit($jsonOBJ) {
        // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
        $this->data = array(
            'status'  => 'error',
            'message' => 'La consulta falló'
        );
        // SE VERIFICA HABER RECIBIDO EL ID
        if( isset($jsonOBJ->id) ) {
            // SE REALIZA LA QUERY DE BÚSQUEDA Y AL MISMO TIEMPO SE VALIDA SI HUBO RESULTADOS
            $sql =  "UPDATE proyectos SET nombre='{$jsonOBJ->name}', descripcion='{$jsonOBJ->descripcion}' WHERE id={$jsonOBJ->id}";
            $this->conexion->set_charset("utf8");
            if ( $this->conexion->query($sql) ) {
                $this->data['status'] =  "success";
                $this->data['message'] =  "Proyecto actualizado";
            } else {
                $this->data['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($this->conexion);
            }
            $this->conexion->close();
        }
    }
}

?>