<?php
header('Content-Type: application/json');

$conexion = new mysqli('localhost', 'root', '12345678', 'proyecto_db');
if ($conexion->connect_error) {
    die(json_encode(['error' => 'Error de conexión: ' . $conexion->connect_error]));
}

$sql = "SELECT mensaje, tipo, fecha FROM notificaciones ORDER BY fecha DESC LIMIT 10";
$resultado = $conexion->query($sql);

$notificaciones = [];
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $notificaciones[] = $row;
    }
}

$conexion->close();
echo json_encode($notificaciones);
?>
