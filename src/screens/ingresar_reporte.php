<?php
// Habilitar la detección de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión
session_start();
include 'config.php'; // Archivo con la conexión a la base de datos

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar si hay objetivos en la sesión
$objetivosSeleccionados = isset($_SESSION['objetivos']) ? $_SESSION['objetivos'] : [];
if (empty($objetivosSeleccionados)) {
    echo "No hay objetivos seleccionados. <a href='objectives.php'>Volver</a>";
    exit;
}

// Obtener los proyectos creados por el usuario
$user_id = $_SESSION['user_id'];
$sql = "SELECT id, nombre FROM proyectos WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$proyectos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Reporte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@200..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <style>
            * {
                font-family: "Noto Serif JP", serif;
                font-optical-sizing: auto;
                font-weight: 200;
                font-style: normal;
            }
    </style>
    
</head>
<body>
    <div class="container my-5">
        <h3 class="text-center">Generar Reporte</h3>

        <!-- Mostrar mensajes de error o éxito -->
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="guardar_reporte.php" method="post">
            <!-- Selección de proyecto -->
            <div class="mb-3">
                <label for="proyecto" class="form-label">Selecciona un Proyecto</label>
                <select class="form-control" name="proyecto_id" id="proyecto" required>
                    <option value="">Selecciona un proyecto</option>
                    <?php foreach ($proyectos as $proyecto): ?>
                        <option value="<?= htmlspecialchars($proyecto['id']); ?>">
                            <?= htmlspecialchars($proyecto['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Información básica del reporte -->
            <div class="mb-3">
                <label for="projectManager" class="form-label">Responsable del Proyecto</label>
                <input type="text" class="form-control" name="projectManager" id="projectManager" placeholder="Nombre del Responsable" required>
            </div>
            <div class="mb-3">
                <label for="startDate" class="form-label">Fecha de Inicio del Reporte</label>
                <input type="date" class="form-control" name="startDate" id="startDate" required>
            </div>
            <div class="mb-3">
                <label for="endDate" class="form-label">Fecha de Fin del Reporte</label>
                <input type="date" class="form-control" name="endDate" id="endDate" required>
            </div>

            <!-- Campos dinámicos según objetivos -->
            <?php if (in_array('agua', $objetivosSeleccionados)): ?>
                <div class="mb-3">
                    <label for="waterUsage" class="form-label">Consumo Total de Agua (m³)</label>
                    <input type="number" class="form-control" name="waterUsage" id="waterUsage" placeholder="Cantidad en m³">
                </div>
                <div class="mb-3">
                    <label for="waterCost" class="form-label">Costo Total del Agua ($ MXN)</label>
                    <input type="number" class="form-control" name="waterCost" id="waterCost" placeholder="Costo en pesos MXN">
                </div>
            <?php endif; ?>

            <?php if (in_array('energia', $objetivosSeleccionados)): ?>
                <div class="mb-3">
                    <label for="energyUsage" class="form-label">Consumo Energético Total (kWh)</label>
                    <input type="number" class="form-control" name="energyUsage" id="energyUsage" placeholder="Cantidad en kWh">
                </div>
                <div class="mb-3">
                    <label for="energyCost" class="form-label">Costo Total de Energía ($ MXN)</label>
                    <input type="number" class="form-control" name="energyCost" id="energyCost" placeholder="Costo en pesos mexicanos">
                </div>
            <?php endif; ?>

            <?php if (in_array('operacion', $objetivosSeleccionados)): ?>
                <div class="mb-3">
                    <label for="operationalExpenses" class="form-label">Gastos Operativos Totales ($ MXN)</label>
                    <input type="number" class="form-control" name="operationalExpenses" id="operationalExpenses" placeholder="Costo en pesos MXN">
                </div>
                <div class="mb-3">
                    <label for="budget" class="form-label">Presupuesto Total ($ MXN)</label>
                    <input type="number" class="form-control" name="budget" id="budget" placeholder="Presupuesto en pesos MXN">
                </div>
                <div class="mb-3">
                    <label for="budgetVariance" class="form-label">Variación Presupuestaria ($ MXN)</label>
                    <input type="number" class="form-control" name="budgetVariance" id="budgetVariance" placeholder="Variación en pesos MXN">
                </div>
            <?php endif; ?>

            <!-- Observaciones -->
            <div class="mb-3">
                <label for="observations" class="form-label">Observaciones</label>
                <textarea class="form-control" name="observations" id="observations" rows="4" placeholder="Notas adicionales"></textarea>
            </div>

            <!-- Botón de enviar -->
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-large">Guardar Reporte</button>
            </div>
        </form>
    </div>
</body>
</html>
