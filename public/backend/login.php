<?php
// Incluir la conexión a la base de datos
include 'config.php';

// Comprobar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Buscar el usuario en la base de datos (por nombre de usuario o correo)
    $sql = "SELECT * FROM usuarios WHERE username = '$username' OR email = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Si el usuario existe, obtener sus datos
        $user = $result->fetch_assoc();

        // Verificar si la contraseña es correcta
        if (password_verify($password, $user['password'])) {
            // Iniciar sesión (crear una sesión PHP)
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirigir al usuario a una página protegida o a su perfil
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "El usuario no existe.";
    }

    $conn->close();
}
?>
