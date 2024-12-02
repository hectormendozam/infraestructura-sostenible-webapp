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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTrack</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="src/img/planeta.png" type="image/x-icon">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="text-center mb-4">Registro de Cuenta</h3>
                <form action="../../backend/registro.php" method="POST">
                    <!-- Nombre de Usuario -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Ingrese su nombre de usuario" required>
                    </div>
                    
                    <!-- Correo Electrónico -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese su correo electrónico" required>
                    </div>
                    
                    <!-- Contraseña -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su contraseña" required>
                    </div>
                    
                    <!-- Nombre de Empresa o Proyecto -->
                    <div class="mb-3">
                        <label for="company" class="form-label">Nombre de Empresa o Proyecto</label>
                        <input type="text" class="form-control" id="company" name="company" placeholder="Ingrese el nombre de su empresa o proyecto" required>
                    </div>
                    
                    <!-- Ubicación -->
                    <div class="mb-3">
                        <label for="ubicacion" class="form-label">Ubicación</label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" placeholder="Ingrese la ubicación" required>
                    </div>
                    
                    <!-- Botón de Enviar -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
