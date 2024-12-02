<?php
include 'config.php';
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
    <title>EcoTrack - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="" type="image/x-icon">
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
            <!-- Encabezado -->
            <div class="bg-primary text-white rounded p-3 mb-4 text-center">
            <h1>Bienvenido al Dashboard</h1>
            </div>
            <!-- Tarjetas del dashborad -->
            <div class="row g-3">
            <div class="col-md-4">
                <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Gráfica de barras</h5>
                    <p class="card-text">Aqui va a ir una gráfica de barras</p>
                </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Gráfica de circulo</h5>
                    <p class="card-text">Aqui va una grafica de circulo</p>
                </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Otra gráfica</h5>
                    <p class="card-text">Aqui va otra grafica mas</p>
                </div>
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