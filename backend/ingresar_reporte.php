<?php
// Conectar a la base de datos
$conexion = new mysqli('localhost', 'root', '12345678', 'proyecto_db');
if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

// Validar si se recibieron datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : null;
    $cantidad = isset($_POST['cantidad']) ? (float) $_POST['cantidad'] : null;

    // Validar los datos recibidos
    if (!$tipo || !$cantidad || !in_array($tipo, ['agua', 'energia', 'operacion'])) {
        echo "Datos inválidos. <a href='ingresar_reporte.html'>Volver</a>";
        exit;
    }

    // Insertar el reporte en la base de datos
    $consulta = $conexion->prepare("INSERT INTO reporte (tipo, cantidad) VALUES (?, ?)");
    $consulta->bind_param('sd', $tipo, $cantidad);
    if ($consulta->execute()) {
        echo "<h3>Reporte guardado correctamente</h3>";
        echo "<a href='dashboard.html'>Ir al Dashboard</a>";
    } else {
        echo "Error al guardar el reporte: " . $conexion->error;
    }

    $consulta->close();
} else {
    echo "Método no permitido.";
}

// Cerrar la conexión
$conexion->close();
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
