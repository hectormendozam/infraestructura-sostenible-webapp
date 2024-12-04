<?php
namespace ECOTRACK\MYAPI\Read;

use ECOTRACK\MYAPI\Database;
require_once __DIR__ . '/../../vendor/autoload.php';
class Read extends Database {

    public function __construct($db, $user ='root', $pass ='12345678') {
        parent::__construct($user, $pass, $db);
    }

    public function list() {
            // Obtener proyectos del usuario autenticado
            $user_id = $_SESSION['user_id'];
            $sql = "SELECT id, nombre, descripcion FROM proyectos WHERE usuario_id = ? AND eliminado = 0";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $projects = [];
            while ($row = $result->fetch_assoc()) {
                $projects[] = $row;
            }

            echo json_encode($projects);

            $stmt->close();
            $conn->close();
        }

    public function search($search) {
        // SE VERIFICA HABER RECIBIDO EL ID
        if( isset($search) ) {
            // SE REALIZA LA QUERY DE BÚSQUEDA Y AL MISMO TIEMPO SE VALIDA SI HUBO RESULTADOS
            $sql = "SELECT * FROM proyectos WHERE (id = '{$search}' OR nombre LIKE '%{$search}%' OR descripcion LIKE '%{$search}%' AND eliminado = 0";
            if ( $result = $this->conexion->query($sql) ) {
                // SE OBTIENEN LOS RESULTADOS
                $rows = $result->fetch_all(MYSQLI_ASSOC);

                if(!is_null($rows)) {
                    // SE CODIFICAN A UTF-8 LOS DATOS Y SE MAPEAN AL ARREGLO DE RESPUESTA
                    foreach($rows as $num => $row) {
                        foreach($row as $key => $value) {
                            $this->data[$num][$key] = $value;
                        }
                    }
                }
                $result->free();
            } else {
                die('Query Error: '.mysqli_error($this->conexion));
            }
            $this->conexion->close();
        }
    }

    public function single($id) {
        if( isset($id) ) {
            // SE REALIZA LA QUERY DE BÚSQUEDA Y AL MISMO TIEMPO SE VALIDA SI HUBO RESULTADOS
            if ( $result = $this->conexion->query("SELECT * FROM proyectos WHERE id = {$id}") ) {
                // SE OBTIENEN LOS RESULTADOS
                $row = $result->fetch_assoc();
    
                if(!is_null($row)) {
                    // SE CODIFICAN A UTF-8 LOS DATOS Y SE MAPEAN AL ARREGLO DE RESPUESTA
                    foreach($row as $key => $value) {
                        $this->data[$key] = $value;
                    }
                }
                $result->free();
            } else {
                die('Query Error: '.mysqli_error($this->conexion));
            }
            $this->conexion->close();
        }
    }
}

?>