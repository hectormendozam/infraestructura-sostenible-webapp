<?php
// Incluir la conexión a la base de datos
include 'config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si no está autenticado, redirigir al login
    header("Location: login.php");
    exit();
}

// Obtener el ID del usuario actual
$user_id = $_SESSION['user_id'];

// Comprobar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombreProyecto = $_POST['projectName'];
    $codigoProyecto = $_POST['projectCode'];
    $responsable = $_POST['projectManager'];
    $fechaInicio = $_POST['startDate'];
    $fechaFin = $_POST['endDate'];
    $consumoAgua = isset($_POST['waterUsage']) ? $_POST['waterUsage'] : NULL;
    $costoAgua = isset($_POST['waterCost']) ? $_POST['waterCost'] : NULL;
    $consumoEnergia = isset($_POST['energyUsage']) ? $_POST['energyUsage'] : NULL;
    $costoEnergia = isset($_POST['energyCost']) ? $_POST['energyCost'] : NULL;
    $gastosOperativos = isset($_POST['operationalExpenses']) ? $_POST['operationalExpenses'] : NULL;
    $presupuesto = isset($_POST['budget']) ? $_POST['budget'] : NULL;
    $variacionPresupuesto = isset($_POST['budgetVariance']) ? $_POST['budgetVariance'] : NULL;
    $observaciones = isset($_POST['observations']) ? $_POST['observations'] : NULL;

    // Preparar la consulta para insertar los datos
    $sql = "INSERT INTO reportes 
            (user_id, nombre_proyecto, codigo_proyecto, responsable, fecha_inicio, fecha_fin, 
            consumo_agua, costo_agua, consumo_energia, costo_energia, 
            gastos_operativos, presupuesto, variacion_presupuesto, observaciones) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "issssssdddddds", 
        $user_id, $nombreProyecto, $codigoProyecto, $responsable, $fechaInicio, $fechaFin, 
        $consumoAgua, $costoAgua, $consumoEnergia, $costoEnergia, 
        $gastosOperativos, $presupuesto, $variacionPresupuesto, $observaciones
    );

    // Ejecutar la consulta y verificar si fue exitosa
    if ($stmt->execute()) {
        echo "Reporte guardado con éxito.";
        header("Location: dashboard.php"); // Redirigir al dashboard o a otra página
        exit();
    } else {
        echo "Error al guardar el reporte: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
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
        <form action="guardar_reporte.php" method="post">
            <input type="hidden" name="objetivosSeleccionados" value="<?php echo implode(',', $objetivosSeleccionados); ?>">

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

            <!-- Mostrar campos según objetivos seleccionados -->
            <?php if (in_array('agua', $objetivosSeleccionados)): ?>
                <div class="mb-3">
                    <label for="waterUsage" class="form-label">Consumo Total de Agua (m³)</label>
                    <input type="number" class="form-control" name="waterUsage" id="waterUsage" placeholder="Cantidad en m³" required>
                </div>
                <div class="mb-3">
                    <label for="waterCost" class="form-label">Costo Total del Agua ($ MXN)</label>
                    <input type="number" class="form-control" name="waterCost" id="waterCost" placeholder="Costo en pesos MXN" required>
                </div>
            <?php endif; ?>

            <?php if (in_array('energia', $objetivosSeleccionados)): ?>
                <div class="mb-3">
                    <label for="energyUsage" class="form-label">Consumo Energético Total (kWh)</label>
                    <input type="number" class="form-control" name="energyUsage" id="energyUsage" placeholder="Cantidad en kWh" required>
                </div>
                <div class="mb-3">
                    <label for="energyCost" class="form-label">Costo Total de Energía ($ MXN)</label>
                    <input type="number" class="form-control" name="energyCost" id="energyCost" placeholder="Costo en pesos mexicanos" required>
                </div>
            <?php endif; ?>

            <?php if (in_array('operacion', $objetivosSeleccionados)): ?>
                <div class="mb-3">
                    <label for="operationalExpenses" class="form-label">Gastos Operativos Totales ($ MXN)</label>
                    <input type="number" class="form-control" name="operationalExpenses" id="operationalExpenses" placeholder="Costo en pesos MXN">
                </div>
                <div class="mb-3">
                    <label for="budget" class="form-label">Presupuesto Total ($ MXN)</label>
                    <input type="number" class="form-control" name="budget" id="budget" placeholder="Presupuesto en pesos MXN" required>
                </div>
                <div class="mb-3">
                    <label for="budgetVariance" class="form-label">Variación Presupuestaria ($ MXN)</label>
                    <input type="number" class="form-control" name="budgetVariance" id="budgetVariance" placeholder="Variación en pesos MXN" required>
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
