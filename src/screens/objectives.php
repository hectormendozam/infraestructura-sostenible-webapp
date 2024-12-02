<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Recibir los objetivos seleccionados
$objetivosSeleccionados = isset($_POST['objetivos']) ? $_POST['objetivos'] : [];

if (empty($objetivosSeleccionados)) {
    echo "No se seleccionaron objetivos. <a href='objectives.html'>Volver</a>";
    exit();
}

// Guardar los objetivos en la sesión
$_SESSION['objetivos'] = $objetivosSeleccionados;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTrack - Confirmar Acción</title>
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
                    <a href="index.html" class="nav-link text-white">Inicio</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="dashboard.html" class="nav-link text-white">Estadísticas</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="objectives.html" class="nav-link text-white">Metas y Objetivos</a>
                </li>
                <li class="nav-item mb-2">
                    <a href="profile.html" class="nav-link text-white">Perfil</a>
                </li>
                <li class="nav-item">
                    <a href="login.html" class="nav-link text-white">Salir</a>
                </li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <div class="flex-grow-1 bg-light d-flex justify-content-center align-items-center flex-column">
            <div class="container my-5 text-center">
                <h3>¿Qué deseas hacer?</h3>
                <p class="my-3">Puedes generar un nuevo reporte en base a los objetivos seleccionados o regresar a la página de objetivos.</p>
                <div class="d-flex justify-content-center gap-3">
                    <!-- Botón para generar un nuevo reporte -->
                    <form action="backend/ingresar_reporte.php" method="post">
                        <button type="submit" class="btn btn-primary btn-lg">Generar Reporte</button>
                    </form>
                    <!-- Botón para volver a la página de objetivos -->
                    <a href="objectives.html" class="btn btn-secondary btn-lg">Volver a Objetivos</a>
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
