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
    <style>
        #notificationPanel {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 20px;
            width: 100%;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        #notificationPanel ul {
            list-style: none;
            margin: 0;
            padding: 10px;
            max-height: 300px;
            overflow-y: auto;
        }
        #notificationPanel ul li {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            color: #000; /* Cambia el color del texto a negro */
        }
        #notificationPanel ul li:last-child {
            border-bottom: none;
        }
    </style>
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

            <!-- Panel de Notificaciones -->
            <div id="notificationPanel">
                <h5 class="text-center mt-2">Notificaciones</h5>
                <ul id="notificacionesLista" class="list-group"></ul>
            </div>
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
        // Obtener notificaciones
        async function fetchNotificaciones() {
            const response = await fetch('get_notifications.php');
            return response.json();
        }

        fetchNotificaciones().then(data => {
            const notificacionesLista = document.getElementById('notificacionesLista');
            notificacionesLista.innerHTML = '';

            if (data.length > 0) {
                data.forEach(n => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.textContent = `${n.fecha}: ${n.mensaje}`;
                    notificacionesLista.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.className = 'list-group-item text-muted';
                li.textContent = 'No hay notificaciones.';
                notificacionesLista.appendChild(li);
            }
        }).catch(err => console.error('Error al cargar notificaciones:', err));

        // Reportes recientes
        document.addEventListener('DOMContentLoaded', () => {
            const reportesRecientesTable = document.getElementById('reportesRecientes');

            async function fetchReportesRecientes() {
                const response = await fetch('get_reportes_recientes.php');
                return await response.json();
            }

            fetchReportesRecientes()
                .then(data => {
                    reportesRecientesTable.innerHTML = '';

                    if (data.length > 0) {
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
                            reportesRecientesTable.innerHTML += row;
                        });
                    } else {
                        reportesRecientesTable.innerHTML = `
                            <tr>
                                <td colspan="5" class="text-center">No hay reportes recientes.</td>
                            </tr>
                        `;
                    }
                })
                .catch(err => console.error('Error al cargar reportes recientes:', err));
        });
    </script>
</body>
</html>
