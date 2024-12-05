<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener los últimos 5 reportes creados por el usuario
$sql = "SELECT 
            r.id,
            r.fecha_inicio,
            r.fecha_fin,
            r.observaciones,
            p.nombre AS proyecto
        FROM reportes r
        INNER JOIN proyectos p ON r.proyecto_id = p.id
        WHERE r.user_id = ?
        ORDER BY r.id DESC
        LIMIT 5";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$reportes = [];
while ($row = $result->fetch_assoc()) {
    $reportes[] = $row;
}

echo json_encode($reportes);

$stmt->close();
$conn->close();
?>
