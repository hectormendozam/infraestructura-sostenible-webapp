<?php
namespace Backend\MYAPI;

abstract class Database {
    protected $conexion;
    protected $data;

    public function __construct($user, $pass, $db) {
        $this->data = array();
        $this->conexion = @mysqli_connect(
            'localhost',
            $user,
            $pass,
            $db
        );
    
        /**
         * NOTA: si la conexión falló $conexion contendrá false
         **/
        if(!$this->conexion) {
            die('¡Base de datos NO conectada!');
        }
        /*else {
            echo 'Base de datos encontrada';
        }*/
    }

    public function getData() {
        // SE HACE LA CONVERSIÓN DE ARRAY A JSON
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
?>