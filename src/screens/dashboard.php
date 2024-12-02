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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <!-- Gráficas -->
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Costos Totales</h5>
                            <canvas id="costosChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Consumo de Energía</h5>
                            <canvas id="energiaChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Consumo de Agua</h5>
                            <canvas id="aguaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function fetchData() {
            const response = await fetch('../backend/dashboard_data.php');
            return response.json();
        }

        fetchData().then(data => {
            // Costos
            const proyectosCostos = data.costos.map(item => item.proyecto);
            const valoresCostos = data.costos.map(item => item.costo_total);

            new Chart(document.getElementById('costosChart'), {
                type: 'bar',
                data: {
                    labels: proyectosCostos,
                    datasets: [{
                        label: 'Costos Totales',
                        data: valoresCostos,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });

            // Energía
            const proyectosEnergia = data.energia.map(item => item.proyecto);
            const valoresEnergia = data.energia.map(item => item.energia_total);

            new Chart(document.getElementById('energiaChart'), {
                type: 'line',
                data: {
                    labels: proyectosEnergia,
                    datasets: [{
                        label: 'Consumo de Energía',
                        data: valoresEnergia,
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });

            // Agua
            const proyectosAgua = data.agua.map(item => item.proyecto);
            const valoresAgua = data.agua.map(item => item.agua_total);

            new Chart(document.getElementById('aguaChart'), {
                type: 'pie',
                data: {
                    labels: proyectosAgua,
                    datasets: [{
                        label: 'Consumo de Agua',
                        data: valoresAgua,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                    }]
                },
                options: { responsive: true }
            });
        });
    </script>
</body>
</html>
