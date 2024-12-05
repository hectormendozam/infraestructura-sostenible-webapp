<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si no está autenticado, redirigir al login
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTrack - Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="src/img/planeta.png" type="image/x-icon">
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

        <div class="flex-grow-1 bg-light p-4">
            <!-- Barra de navegación superior -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 rounded">
                <div class="container-fluid">
                    <a class="navbar-brand" href="#">Proyectos de Infraestructura</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarContent">
                        <form class="d-flex ms-auto">
                            <input class="form-control me-2" name="search" id="search" type="search" placeholder="Buscar ID o nombre" aria-label="Search">
                            <button class="btn btn-light" type="submit">Buscar</button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Contenido principal en columnas -->
            <div class="row">
                <!-- Columna del formulario -->
                <div class="col-md-4">
                    <form id="project-form">
                        <div class="form-group">
                            <fieldset>
                                <input class="form-control mb-3" type="text" id="name" name="name" placeholder="Nombre de proyecto">
                                <textarea class="form-control mb-3" id="description" name="description" placeholder="Detalles del proyecto"></textarea>
                            </fieldset>                
                        </div>
                        <input type="hidden" id="projectId">
                        <input type="hidden" id="user_id" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
                        <button class="btn btn-primary w-100" type="submit">Agregar Proyecto</button>
                    </form>
                </div>
                
                <!-- TABLA  -->
                <div class="col-md-8">
                    <div class="card my-4 d-none" id="project-result">
                        <div class="card-body">
                            <!-- RESULTADO -->
                            <ul id="container"></ul>
                        </div>
                    </div>

                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <td>Id</td>
                                <td>Nombre</td>
                                <td>Descripción</td>
                            </tr>
                        </thead>
                        <tbody id="projects"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>
    <!-- Lógica del Frontend -->
    <script src="../app.js"></script>
</body>
</html>
