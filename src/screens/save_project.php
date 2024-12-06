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
    // Validar que se hayan enviado los campos requeridos
    $requiredFields = ['proyecto_id', 'name', 'description'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            die("Error: Por favor, completa todos los campos obligatorios.");
        }
    }

    // Obtener los valores del formulario
    $id = $_POST['proyecto_id'];
    $nombre = $_POST['name'];
    $descripcion = $_POST['description'];
    $usuario_id = $_SESSION['user_id']; // Usar el ID del usuario autenticado

    // Actualizar el proyecto en la base de datos
    $sql = "UPDATE proyectos 
            SET nombre = ?, descripcion = ?
            WHERE id = ? AND usuario_id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }

    // Vincular parámetros y ejecutar la consulta
    $stmt->bind_param("ssii", $nombre, $descripcion, $id, $usuario_id);
    if ($stmt->execute()) {
        // Redirigir o mostrar mensaje de éxito si la actualización fue exitosa
        echo "<script>
                alert('Los cambios se guardaron correctamente.');
                window.location.href = 'index.php';
                </script>";
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
