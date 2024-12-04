<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Conectar a la base de datos
include '../screens/config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

// Obtener proyectos del usuario autenticado
$user_id = $_SESSION['user_id'];
$sql = "SELECT id, nombre, descripcion FROM proyectos WHERE usuario_id = ?";
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
?>
