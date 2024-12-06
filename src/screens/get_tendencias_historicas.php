<?php
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "
    SELECT 
        fecha_inicio,
        SUM(consumo_energia) AS consumo_energia,
        SUM(consumo_agua) AS consumo_agua,
        SUM(gastos_operativos) AS gastos_operativos
    FROM reportes
    WHERE user_id = ?
    GROUP BY fecha_inicio
    ORDER BY fecha_inicio ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$tendencias = [];
while ($row = $result->fetch_assoc()) {
    $tendencias[] = $row;
}

echo json_encode($tendencias);

$stmt->close();
$conn->close();
?>
