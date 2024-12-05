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
        'Consumo de Agua' AS objetivo, SUM(consumo_agua) AS valor FROM reportes WHERE user_id = ? AND consumo_agua IS NOT NULL
    UNION ALL
    SELECT 
        'Costo de Agua', SUM(costo_agua) FROM reportes WHERE user_id = ? AND costo_agua IS NOT NULL
    UNION ALL
    SELECT 
        'Consumo de Energía', SUM(consumo_energia) FROM reportes WHERE user_id = ? AND consumo_energia IS NOT NULL
    UNION ALL
    SELECT 
        'Gastos Operativos', SUM(gastos_operativos) FROM reportes WHERE user_id = ? AND gastos_operativos IS NOT NULL
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $user_id, $user_id, $user_id, $user_id);
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
