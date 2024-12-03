<?php
$conexion = new mysqli('localhost', 'root', '12345678', 'proyecto_db');
if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

// 1. Detectar superación de objetivos
$sql_objetivos = "
    SELECT 
        p.nombre AS proyecto, 
        o.tipo, 
        o.cantidad AS objetivo_cantidad, 
        COALESCE(SUM(r.cantidad), 0) AS reportado_cantidad
    FROM proyectos p
    JOIN objetivo o ON p.id = o.proyecto_id
    LEFT JOIN reporte r ON p.id = r.proyecto_id AND o.tipo = r.tipo
    GROUP BY p.id, o.tipo
    HAVING reportado_cantidad > objetivo_cantidad
";

$resultado_objetivos = $conexion->query($sql_objetivos);
if ($resultado_objetivos->num_rows > 0) {
    while ($row = $resultado_objetivos->fetch_assoc()) {
        $mensaje = "El proyecto '{$row['proyecto']}' ha superado el objetivo de {$row['tipo']}. Reportado: {$row['reportado_cantidad']}, Objetivo: {$row['objetivo_cantidad']}.";
        $conexion->query("INSERT INTO notificaciones (mensaje, tipo) VALUES ('$mensaje', 'objetivo')");
    }
}

// 2. Detectar consumos inusuales
$sql_consumo = "
    SELECT 
        r.tipo, 
        AVG(r.cantidad) AS promedio_consumo, 
        p.nombre AS proyecto, 
        MAX(r.cantidad) AS consumo_maximo
    FROM reporte r
    JOIN proyectos p ON p.id = r.proyecto_id
    GROUP BY r.tipo, p.id
    HAVING consumo_maximo > (promedio_consumo * 1.5)
";

$resultado_consumo = $conexion->query($sql_consumo);
if ($resultado_consumo->num_rows > 0) {
    while ($row = $resultado_consumo->fetch_assoc()) {
        $mensaje = "Consumo inusual detectado en el proyecto '{$row['proyecto']}' para {$row['tipo']}. Máximo: {$row['consumo_maximo']}, Promedio: {$row['promedio_consumo']}.";
        $conexion->query("INSERT INTO notificaciones (mensaje, tipo) VALUES ('$mensaje', 'consumo')");
    }
}

$conexion->close();
echo "Notificaciones generadas correctamente.";
?>
