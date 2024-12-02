<?php
// Configuración de la base de datos
$servername = "localhost"; // Cambia si tu servidor no está en localhost
$username = "root"; // Cambia por tu usuario de MySQL
$password = "12345678"; // Cambia por tu contraseña de MySQL
$dbname = "proyecto_db"; // La base de datos que acabamos de crear

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
