<?php
require_once '../src/screens/config.php';

session_start();
$user_id = $_SESSION['user_id'];

// Obtén los datos del formulario
$email = $_POST['email'];
$username = $_POST['username'];
$ubicacion = $_POST['ubicacion'];
$company = $_POST['company'];

// Actualiza los datos en la base de datos
$sql = "UPDATE usuarios SET username = ?, email = ?, company = ?, ubicacion = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $username, $email, $company, $ubicacion, $user_id);

if ($stmt->execute()) {
    echo "Perfil actualizado exitosamente.";
    header("Location: ../src/screens/profile.php");
} else {
    echo "Error al actualizar el perfil: " . $stmt->error;
}
?>