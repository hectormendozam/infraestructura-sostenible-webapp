<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si no está autenticado, redirigir al login
    header("Location: login.html");
    exit();
}

// Aquí puedes mostrar información del usuario
echo "<h1>Bienvenido, " . $_SESSION['username'] . "!</h1>";
echo "<p>Este es tu panel de control.</p>";
?>
