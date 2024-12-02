<?php
header('Content-Type: application/json');
$conexion = new mysqli('localhost', 'root', '12345678', 'proyecto_db');

if ($conexion->connect_error) {
    die(json_encode(['error' => 'Error de conexión: ' . $conexion->connect_error]));
}

$data = [];

// Obtener datos para cada gráfica
$sql_costos = "
    SELECT p.nombre AS proyecto, SUM(r.cantidad) AS costo_total
    FROM proyectos p
    JOIN reporte r ON p.id = r.proyecto_id
    WHERE r.tipo = 'operacion'
    GROUP BY p.id
";

$sql_energia = "
    SELECT p.nombre AS proyecto, SUM(r.cantidad) AS energia_total
    FROM proyectos p
    JOIN reporte r ON p.id = r.proyecto_id
    WHERE r.tipo = 'energia'
    GROUP BY p.id
";

$sql_agua = "
    SELECT p.nombre AS proyecto, SUM(r.cantidad) AS agua_total
    FROM proyectos p
    JOIN reporte r ON p.id = r.proyecto_id
    WHERE r.tipo = 'agua'
    GROUP BY p.id
";

// Ejecutar consultas y preparar datos
$data['costos'] = $conexion->query($sql_costos)->fetch_all(MYSQLI_ASSOC);
$data['energia'] = $conexion->query($sql_energia)->fetch_all(MYSQLI_ASSOC);
$data['agua'] = $conexion->query($sql_agua)->fetch_all(MYSQLI_ASSOC);

$conexion->close();
echo json_encode($data);
?>
