<?php
// Conectar a la base de datos
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado.']);
    exit();
}

// Obtener datos enviados desde el formulario
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : null;
$descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;
$user_id = $_SESSION['user_id'];

if (!$nombre || !$descripcion) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios.']);
    exit();
}

// Insertar el proyecto en la base de datos
$sql = "INSERT INTO proyectos (nombre, descripcion, usuario_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $nombre, $descripcion, $user_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Proyecto agregado exitosamente.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al agregar el proyecto.']);
}

$stmt->close();
$conn->close();
?>
