<?php
include 'config.php';
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si no está autenticado, redirigir al login
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
// Obtener los datos del usuario de la base de datos
$sql = "SELECT username, email, ubicacion, company FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $ubicacion, $company);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTrack - Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="src/img/planeta.png" type="image/x-icon">
</head>
<body>
    <div class="d-flex">
        <!-- Menú de navegación -->
        <nav class="bg-dark text-white vh-100 p-3" style="width: 250px;">
            <!-- Imagen del logo arriba del título -->
            <h2 class="text-center">EcoTrack</h2>
            <div class="text-center mb-3">
                <img src="../img/Ecotrack.png" alt="EcoTrack Logo" style="width: 100px; height: 100px;">
            </div>
            <ul class="nav flex-column mt-4">
                <li class="nav-item mb-2">
                    <a href="index.php" class="nav-link text-white">Inicio</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white">Estadísticas</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="objectives.php" class="nav-link text-white">Metas y Objetivos</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="profile.php" class="nav-link text-white">Perfil</a>
                </li>
                <li class="nav-item">
                    <a href="login.php" class="nav-link text-white">Salir</a>
                </li>
            </ul>
        </nav>

        <div class="flex-grow-1 bg-light p-4">
            <div class="container">
                <!-- Encabezado -->
                <div class="bg-primary text-white rounded p-3 mb-4 text-center">
                    <h1>Perfil de Usuario</h1>
                </div>

                <script>
                    const userData = <?php echo json_encode($user); ?>;
                    console.log(userData);
                </script>
                
                <!-- Información del usuario -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Información Personal</h5>
                        <form id="profileForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="username" class="form-label">Nombre de Usuario</label>
                                    <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($username); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($email); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="location" class="form-label">Ubicación</label>
                                    <input type="text" class="form-control" id="location" value="<?php echo htmlspecialchars($ubicacion); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="role" class="form-label">Empresa</label>
                                    <input type="text" class="form-control" id="company" value="<?php echo htmlspecialchars($company); ?>" readonly>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="button" class="btn btn-primary" id="editProfile">Editar Perfil</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
    <!-- Lógica del Frontend -->
    <script src="src/app.js"></script>
    
</body>
</html>