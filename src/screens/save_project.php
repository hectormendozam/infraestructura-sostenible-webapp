<?php
// Habilitar la detección de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config.php'; // Archivo con la conexión a la base de datos

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que se haya enviado un proyecto y que los campos requeridos no estén vacíos
    $requiredFields = ['proyecto_id', 'name', 'description', 'user_id'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            die("Error: Por favor, completa todos los campos obligatorios.");
        }
    }

    $id = $_POST['proyecto_id'];
    $nombre = $_POST['name'];
    $descripcion = $_POST['description'];
    $usuario_id = $_POST['user_id'];

    // Insertar el reporte en la base de datos
    $sql = "INSERT INTO proyectos (nombre, descripcion)
            VALUES (?, ?) WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssi",
        $nombre,
        $descripcion,
        $id
    );

    if ($stmt->execute()) {
        // Redirigir a success.php si la inserción fue exitosa
        header("Location: index.php");
        exit();
    } else {
        // Manejar errores en la ejecución
        echo "Error al guardar los cambios: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Método no permitido.";
    exit();
}
