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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="d-flex">
        <!-- Menú de navegación -->
        <nav class="bg-dark text-white vh-100 p-3" style="width: 250px;">
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
                <!-- Progreso hacia los objetivos -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Progreso de Objetivos</h5>
                            <canvas id="progresoChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Comparación de proyectos -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Comparación de Proyectos</h5>
                            <canvas id="comparacionChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Promedio y tendencia -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Promedio y Tendencia</h5>
                            <canvas id="tendenciaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function fetchStatistics() {
            const response = await fetch('../backend/statistics_data.php');
            return response.json();
        }

        fetchStatistics().then(data => {
            // Progreso hacia los objetivos
            const proyectosProgreso = data.progreso.map(item => item.proyecto);
            const progresoPorcentaje = data.progreso.map(item => parseFloat(item.progreso || 0).toFixed(2));

            new Chart(document.getElementById('progresoChart'), {
                type: 'bar',
                data: {
                    labels: proyectosProgreso,
                    datasets: [{
                        label: '% Progreso',
                        data: progresoPorcentaje,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, scales: { y: { beginAtZero: true, max: 100 } } }
            });

            // Comparación de proyectos
            const proyectosComparacion = [...new Set(data.comparacion.map(item => item.proyecto))];
            const tiposComparacion = ['agua', 'energia', 'operacion'];
            const datasetsComparacion = tiposComparacion.map(tipo => ({
                label: tipo.charAt(0).toUpperCase() + tipo.slice(1),
                data: proyectosComparacion.map(proyecto => {
                    const item = data.comparacion.find(c => c.proyecto === proyecto && c.tipo === tipo);
                    return item ? item.total : 0;
                }),
                backgroundColor: tipo === 'agua' ? 'rgba(75, 192, 192, 0.2)' : tipo === 'energia' ? 'rgba(255, 159, 64, 0.2)' : 'rgba(255, 99, 132, 0.2)',
                borderColor: tipo === 'agua' ? 'rgba(75, 192, 192, 1)' : tipo === 'energia' ? 'rgba(255, 159, 64, 1)' : 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }));

            new Chart(document.getElementById('comparacionChart'), {
                type: 'bar',
                data: {
                    labels: proyectosComparacion,
                    datasets: datasetsComparacion
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });

            // Promedio y tendencia
            const tiposTendencia = [...new Set(data.tendencia.map(item => item.tipo))];
            const labelsTendencia = data.tendencia
                .filter((v, i, a) => a.findIndex(t => t.mes === v.mes && t.anio === v.anio) === i)
                .map(item => `${item.mes}/${item.anio}`);

            const datasetsTendencia = tiposTendencia.map(tipo => ({
                label: tipo.charAt(0).toUpperCase() + tipo.slice(1),
                data: data.tendencia.filter(item => item.tipo === tipo).map(item => item.promedio),
                borderColor: tipo === 'agua' ? 'rgba(54, 162, 235, 1)' : tipo === 'energia' ? 'rgba(255, 206, 86, 1)' : 'rgba(75, 192, 192, 1)',
                fill: false,
                tension: 0.1
            }));

            new Chart(document.getElementById('tendenciaChart'), {
                type: 'line',
                data: {
                    labels: labelsTendencia,
                    datasets: datasetsTendencia
                },
                options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });
        });
    </script>
</body>
</html>
