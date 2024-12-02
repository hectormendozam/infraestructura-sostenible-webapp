<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si no está autenticado, redirigir al login
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTrack - Objetivos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="src/img/planeta.png" type="image/x-icon">
    <style>
        /* Fondo de la imagen */
        body {
            position: relative;
            min-height: 100vh;
            background: url('../img/city.jpeg') no-repeat center center;
            background-size: cover;
            background-attachment: fixed;
        }

        /* Fondo semitransparente para el contenido */
        .content {
            background-color: rgba(255, 255, 255, 0.9); /* Fondo blanco con transparencia */
            z-index: 1;
            position: relative;
        }
    </style>
</head>
<body>
    <div class="d-flex content">
        <!-- Menú de navegación -->
        <nav class="bg-dark text-white vh-100 p-3" style="width: 250px;">
            <!-- Imagen del logo arriba del título -->
            <div class="text-center mb-3">
                <img src="../img/Ecotrack.png" alt="EcoTrack Logo" style="width: 100px; height: 100px;">
            </div>
            <h2 class="text-center">EcoTrack</h2>
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
                    <a href="logout.php" class="nav-link text-white">Salir</a>
                </li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <div class="flex-grow-1 bg-light d-flex justify-content-center align-items-center flex-column">
            <div class="mb-4">
                <h1 class="text-primary">Metas y Objetivos</h1>
                <p class="text-secondary text-center">Selecciona una opción para continuar</p>
            </div>
            <div class="d-flex gap-4">
                <!-- Botón de Ingresar Reporte -->
                <a href="ingresar_reporte.php" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center" style="width: 200px; height: 200px;">
                    <i class="bi bi-clipboard-data fs-1"></i>
                    <span class="mt-2">Ingresar Reporte</span>
                </a>
                <!-- Botón de Ingresar Objetivos -->
                <a href="ingresar_objetivos.php" class="btn btn-success btn-lg d-flex align-items-center justify-content-center" style="width: 200px; height: 200px;">
                    <i class="bi bi-flag fs-1"></i>
                    <span class="mt-2">Ingresar Objetivos</span>
                </a>
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