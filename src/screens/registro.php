<?php
// Incluir la conexión a la base de datos
include 'config.php';

// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicializar mensaje de estado
$statusMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña
    $company = $_POST['company'];
    $ubicacion = $_POST['ubicacion']; // Usar el nombre correcto de la columna

    if (!$username || !$email || !$password || !$company || !$ubicacion) {
        $statusMessage = "Todos los campos son obligatorios.";
    } else {
        // Validar si el nombre de usuario o correo ya existen en la base de datos
        $sql_check = "SELECT * FROM usuarios WHERE email = ? OR username = ?";
        $stmt = $conn->prepare($sql_check);
        if (!$stmt) {
            die("Error en la consulta: " . $conn->error);
        }
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $statusMessage = "El nombre de usuario o correo electrónico ya está registrado.";
        } else {
            // Insertar los datos en la base de datos
            $sql = "INSERT INTO usuarios (username, email, password, company, ubicacion) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Error en la consulta: " . $conn->error);
            }
            $stmt->bind_param("sssss", $username, $email, $password, $company, $ubicacion);

            if ($stmt->execute()) {
                // Redirigir al login después de un registro exitoso
                header("Location: login.php");
                exit(); // Asegurar que el script termine después de la redirección
            } else {
                $statusMessage = "Error al registrar: " . $stmt->error;
            }
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTrack - Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="../img/planeta.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@200..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <style>
            * {
                font-family: "Noto Serif JP", serif;
                font-optical-sizing: auto;
                font-weight: 200;
                font-style: normal;
            }
    </style>

</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="text-center mb-4">Registro de Cuenta</h3>

                <?php if (!empty($statusMessage)): ?>
                    <div class="alert alert-info"><?php echo $statusMessage; ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="company" class="form-label">Nombre de Empresa o Proyecto</label>
                        <input type="text" class="form-control" id="company" name="company" required>
                    </div>
                    <div class="mb-3">
                        <label for="ubicacion" class="form-label">Ubicación</label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
