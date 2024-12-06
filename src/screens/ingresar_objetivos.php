<?php
// Iniciar sesión para guardar los objetivos
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Recibir los objetivos seleccionados
$objetivosSeleccionados = isset($_POST['objetivos']) ? $_POST['objetivos'] : [];

// Variable para manejar el estado de los objetivos
$objetivosGuardados = false;

// Guardar los objetivos en la sesión si fueron enviados
if (!empty($objetivosSeleccionados)) {
    $_SESSION['objetivos'] = $objetivosSeleccionados;
    $objetivosGuardados = true;
}
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
        .form-check {
            padding: 20px; /* Añade espacio vertical a cada opción */
            font-size: 1.5rem; /* Aumenta el tamaño del texto */
        }
        .form-check-input {
            width: 25px;
            height: 25px; /* Aumenta el tamaño del checkbox */
        }
        .form-check-label i {
            margin-right: 10px; /* Espacio entre ícono y texto */
            font-size: 2rem; /* Aumenta el tamaño de los íconos */
        }
        h2 {
            margin-top: 5%;
        }
        .bsubmit {
            margin-top: 5%;
        }
        .btn-large {
            font-size: 1.3rem;
        }
    </style>
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
                    <a href="logout.php" class="nav-link text-white">Salir</a>
                </li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <div class="container my-4">
            <?php if ($objetivosGuardados): ?>
                <h2 class="text-center text-primary mb-4">¿Qué deseas hacer?</h2>
                <p class="text-secondary text-center">Puedes generar un nuevo reporte en base a los objetivos seleccionados o regresar a la página de objetivos.</p>
                <div class="d-flex justify-content-center gap-3">
                    <!-- Botón para generar un nuevo reporte -->
                    <form action="backend/ingresar_reporte.php" method="post">
                        <button type="submit" class="btn btn-primary btn-lg">Generar Reporte</button>
                    </form>
                    <!-- Botón para volver a la página de objetivos -->
                    <a href="objectives.php" class="btn btn-secondary btn-lg">Volver a Objetivos</a>
                </div>
            <?php else: ?>
                <h2 class="text-center text-primary mb-4">Ingresa en que áreas quieres llevar un registro</h2>
                <form action="preguntar_accion.php" method="post">
                    <!-- Lista de opciones -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="objetivos[]" value="agua" id="agua">
                        <label class="form-check-label" for="agua">
                            <i class="bi bi-droplet-fill text-primary"></i> Gasto de agua
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="objetivos[]" value="energia" id="energia">
                        <label class="form-check-label" for="energia">
                            <i class="bi bi-lightning-fill text-warning"></i> Consumo energético
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="objetivos[]" value="operacion" id="operacion">
                        <label class="form-check-label" for="operacion">
                            <i class="bi bi-cash-coin text-success"></i> Gastos de operación
                        </label>
                    </div>
                    <!-- Botón de enviar -->
                    <div class="d-grid bsubmit">
                        <button type="submit" class="btn btn-primary btn-large">Guardar Objetivos</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
