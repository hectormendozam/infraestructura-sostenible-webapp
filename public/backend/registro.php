<?php
// Incluir la conexión a la base de datos
include 'config.php';

// Comprobar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña
    $company = $_POST['company'];
    $ubicacion = $_POST['ubicacion'];

    // Validar si el nombre de usuario o correo ya existen en la base de datos
    $sql_check = "SELECT * FROM usuarios WHERE email = '$email' OR username = '$username'";
    $result = $conn->query($sql_check);
    
    if ($result->num_rows > 0) {
        echo "El nombre de usuario o correo electrónico ya está registrado.";
    } else {
        // Insertar los datos en la base de datos
        $sql = "INSERT INTO usuarios (username, email, password, company, ubicacion) 
                VALUES ('$username', '$email', '$password', '$company', '$ubicacion')";

        if ($conn->query($sql) === TRUE) {
            echo "Registro exitoso.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>
