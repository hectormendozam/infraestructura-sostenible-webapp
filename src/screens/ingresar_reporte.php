<?php
// Habilitar la detección de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión para acceder a los objetivos guardados y al usuario actual
session_start();
include 'config.php'; // Archivo con la conexión a la base de datos

// Verificar si hay objetivos en la sesión
$objetivosSeleccionados = isset($_SESSION['objetivos']) ? $_SESSION['objetivos'] : [];
if (empty($objetivosSeleccionados)) {
    echo "No hay objetivos seleccionados. <a href='objectives.php'>Volver</a>";
    exit;
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Procesar la información si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que los campos obligatorios estén presentes
    $requiredFields = ['projectName', 'projectCode', 'projectManager', 'startDate', 'endDate'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $errorMessage = "Por favor completa todos los campos obligatorios.";
            break;
        }
    }

    // Si no hay errores, proceder con la inserción
    if (!isset($errorMessage)) {
        $nombreProyecto = $_POST['projectName'];
        $codigoProyecto = $_POST['projectCode'];
        $responsable = $_POST['projectManager'];
        $fechaInicio = $_POST['startDate'];
        $fechaFin = $_POST['endDate'];
        $user_id = $_SESSION['user_id'];

        // Otros campos opcionales
        $waterUsage = $_POST['waterUsage'] ?? null;
        $waterCost = $_POST['waterCost'] ?? null;
        $energyUsage = $_POST['energyUsage'] ?? null;
        $energyCost = $_POST['energyCost'] ?? null;
        $operationalExpenses = $_POST['operationalExpenses'] ?? null;
        $budget = $_POST['budget'] ?? null;
        $budgetVariance = $_POST['budgetVariance'] ?? null;
        $observations = $_POST['observations'] ?? null;

        // Preparar la consulta
        $sql = "INSERT INTO reportes (user_id, nombre_proyecto, codigo_proyecto, responsable, fecha_inicio, fecha_fin, 
                consumo_agua, costo_agua, consumo_energia, costo_energia, gastos_operativos, presupuesto_total, 
                variacion_presupuesto, observaciones) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "isssssdidddsds",
            $user_id,
            $nombreProyecto,
            $codigoProyecto,
            $responsable,
            $fechaInicio,
            $fechaFin,
            $waterUsage,
            $waterCost,
            $energyUsage,
            $energyCost,
            $operationalExpenses,
            $budget,
            $budgetVariance,
            $observations
        );

        if ($stmt->execute()) {
            $successMessage = "Reporte guardado exitosamente.";
            header("Location: success.php"); // Redirige a una página de éxito (ajusta según tu proyecto)
            exit;
        } else {
            $errorMessage = "Error al guardar el reporte: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Reporte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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

        <form action="ingresar_reporte.php" method="post">
            <!-- Información básica del proyecto -->
            <div class="mb-3">
                <label for="projectName" class="form-label">Nombre del Proyecto</label>
                <input type="text" class="form-control" name="projectName" id="projectName" placeholder="Nombre del Proyecto" required>
            </div>
            <div class="mb-3">
                <label for="projectCode" class="form-label">ID o Código del Proyecto</label>
                <input type="text" class="form-control" name="projectCode" id="projectCode" placeholder="Código del Proyecto" required>
            </div>
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

            <!-- Campos según los objetivos seleccionados -->
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

            <!-- Observaciones generales -->
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
