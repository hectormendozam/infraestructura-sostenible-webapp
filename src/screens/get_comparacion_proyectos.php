<?php
include 'config.php';

// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Usuario no autenticado.']);
    exit();
}

// Obtener el ID del usuario autenticado
$user_id = $_SESSION['user_id'];

// Consulta para obtener datos de comparación
$sql = "
    SELECT 
        proyectos.nombre AS nombre_proyecto,
        SUM(reportes.presupuesto_total) AS presupuesto_total,
        SUM(reportes.consumo_energia) AS consumo_energia,
        SUM(reportes.consumo_agua) AS consumo_agua
    FROM 
        reportes
    INNER JOIN 
        proyectos ON reportes.proyecto_id = proyectos.id
    WHERE 
        proyectos.usuario_id = ?
    GROUP BY 
        proyectos.nombre
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$comparacion = [];
while ($row = $result->fetch_assoc()) {
    $comparacion[] = [
        'nombre_proyecto' => $row['nombre_proyecto'],
        'presupuesto_total' => (float) $row['presupuesto_total'],
        'consumo_energia' => (float) $row['consumo_energia'],
        'consumo_agua' => (float) $row['consumo_agua'],
    ];
}

// Enviar los datos en formato JSON
echo json_encode($comparacion);

$stmt->close();
$conn->close();
?>
