<?php
header('Content-Type: application/json');

$conexion = new mysqli('localhost', 'root', '12345678', 'proyecto_db');
if ($conexion->connect_error) {
    die(json_encode(['error' => 'Error de conexión: ' . $conexion->connect_error]));
}

$data = [];

// 1. Progreso hacia los objetivos
$sql_progreso = "
    SELECT 
        p.nombre AS proyecto, 
        o.tipo AS objetivo_tipo, 
        o.cantidad AS objetivo_cantidad, 
        COALESCE(SUM(r.cantidad), 0) AS reportado_cantidad,
        (COALESCE(SUM(r.cantidad), 0) / o.cantidad) * 100 AS progreso
    FROM proyectos p
    JOIN objetivo o ON p.id = o.proyecto_id
    LEFT JOIN reporte r ON p.id = r.proyecto_id AND o.tipo = r.tipo
    GROUP BY p.id, o.tipo
";

$data['progreso'] = $conexion->query($sql_progreso)->fetch_all(MYSQLI_ASSOC);

// 2. Comparación de proyectos
$sql_comparacion = "
    SELECT 
        p.nombre AS proyecto, 
        o.tipo AS tipo, 
        SUM(r.cantidad) AS total
    FROM proyectos p
    LEFT JOIN reporte r ON p.id = r.proyecto_id
    LEFT JOIN objetivo o ON o.proyecto_id = p.id AND r.tipo = o.tipo
    GROUP BY p.id, o.tipo
";

$data['comparacion'] = $conexion->query($sql_comparacion)->fetch_all(MYSQLI_ASSOC);

// 3. Promedio y tendencia
$sql_promedio_tendencia = "
    SELECT 
        r.tipo, 
        MONTH(r.fecha) AS mes, 
        YEAR(r.fecha) AS anio, 
        AVG(r.cantidad) AS promedio
    FROM reporte r
    GROUP BY r.tipo, YEAR(r.fecha), MONTH(r.fecha)
    ORDER BY r.tipo, anio, mes
";

$data['tendencia'] = $conexion->query($sql_promedio_tendencia)->fetch_all(MYSQLI_ASSOC);

$conexion->close();
echo json_encode($data);
?>
