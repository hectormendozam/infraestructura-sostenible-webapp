<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../screens/config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener los datos de los reportes
$sql = "
    SELECT 
        CASE 
            WHEN water_usage IS NOT NULL THEN 'Agua'
            WHEN energy_usage IS NOT NULL THEN 'Energía'
            WHEN operational_expenses IS NOT NULL THEN 'Operación'
        END AS objetivo,
        COALESCE(water_usage, energy_usage, operational_expenses) AS valor
    FROM reportes
    WHERE usuario_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$progreso = [];
while ($row = $result->fetch_assoc()) {
    $progreso[] = [
        'objetivo' => $row['objetivo'],
        'valor' => (float) $row['valor']
    ];
}

echo json_encode($progreso);

$stmt->close();
$conn->close();
?>
