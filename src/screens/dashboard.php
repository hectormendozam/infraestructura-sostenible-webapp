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
    <link rel="icon" href="src/img/planeta.png" type="image/x-icon">
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
                    <a href="logout.php" class="nav-link text-white">Salir</a>
                </li>
            </ul>
        </nav>

        <div class="flex-grow-1 bg-light p-4">
            <!-- Encabezado -->
            <div class="bg-primary text-white rounded p-3 mb-4 text-center position-relative">
                <h1>Bienvenido al Dashboard</h1>
            </div>
            <!-- Gráficas -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Progreso de Objetivos</h5>
                            <canvas id="progresoChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Comparación de Proyectos</h5>
                            <canvas id="comparacionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-3 mt-4">

            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Reportes Recientes</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Proyecto</th>
                                    <th>Fecha de Inicio</th>
                                    <th>Fecha de Fin</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody id="reportesRecientes">
                                <!-- Los datos serán llenados dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>

    <script>
        // Gráfica de Progreso de Objetivos
        document.addEventListener('DOMContentLoaded', () => {
            const progresoCtx = document.getElementById('progresoChart').getContext('2d');
            async function fetchProgresoObjetivos() {
                const response = await fetch('get_reportes_progreso.php');
                return response.json();
            }

            fetchProgresoObjetivos().then(data => {
                const labels = data.map(d => d.objetivo);
                const valores = data.map(d => d.valor);

                new Chart(progresoCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Progreso de Objetivos',
                            data: valores,
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(153, 102, 255, 0.6)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            }).catch(err => console.error('Error al cargar el progreso de objetivos:', err));
        });

        // Gráfica de Comparación de Proyectos
        document.addEventListener('DOMContentLoaded', () => {
            const comparacionCtx = document.getElementById('comparacionChart').getContext('2d');
            async function fetchComparacionProyectos() {
                const response = await fetch('get_comparacion_proyectos.php');
                return response.json();
            }

            fetchComparacionProyectos()
            .then(data => {
                const labels = data.map(d => d.nombre_proyecto);
                const presupuestos = data.map(d => d.presupuesto_total);
                const consumosEnergia = data.map(d => d.consumo_energia);
                const consumosAgua = data.map(d => d.consumo_agua);

                new Chart(comparacionCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Presupuesto Total ($ MXN)',
                                data: presupuestos,
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Consumo Energético (kWh)',
                                data: consumosEnergia,
                                backgroundColor: 'rgba(255, 206, 86, 0.6)',
                                borderColor: 'rgba(255, 206, 86, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Consumo de Agua (m³)',
                                data: consumosAgua,
                                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(err => console.error('Error al cargar la comparación de proyectos:', err));
        });

// Esperar a que el DOM esté completamente cargado antes de ejecutar el script
document.addEventListener('DOMContentLoaded', () => {
    // Referencia a la tabla donde se mostrarán los reportes recientes
    const reportesRecientesTable = document.getElementById('reportesRecientes');

    // Función asincrónica para obtener los reportes recientes desde el backend
    async function fetchReportesRecientes() {
        try {
            const response = await fetch('get_reportes_recientes.php'); // Llama al endpoint
            return await response.json(); // Devuelve la respuesta como JSON
        } catch (error) {
            console.error('Error al obtener los reportes recientes:', error);
            return [];
        }
    }

    // Llamada para obtener y mostrar los reportes recientes
    fetchReportesRecientes()
        .then(data => {
            // Limpiar el contenido previo de la tabla
            reportesRecientesTable.innerHTML = '';

            if (data.length > 0) {
                // Si hay reportes, generar filas dinámicamente
                data.forEach(reporte => {
                    const row = `
                        <tr>
                            <td>${reporte.id}</td>
                            <td>${reporte.proyecto}</td>
                            <td>${reporte.fecha_inicio}</td>
                            <td>${reporte.fecha_fin}</td>
                            <td>${reporte.observaciones || 'N/A'}</td>
                        </tr>
                    `;
                    // Agregar cada fila al cuerpo de la tabla
                    reportesRecientesTable.innerHTML += row;
                });
            } else {
                // Si no hay reportes, mostrar un mensaje
                reportesRecientesTable.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center">No hay reportes recientes.</td>
                    </tr>
                `;
            }
        })
        .catch(err => {
            // Manejo de errores en la llamada al backend
            console.error('Error al cargar los reportes recientes:', err);
        });
});



    </script>
</body>
</html>
