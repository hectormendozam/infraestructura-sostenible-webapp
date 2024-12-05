<?php
// Habilitar la detección de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config.php'; // Archivo con la conexión a la base de datos

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que se haya enviado un proyecto y que los campos requeridos no estén vacíos
    $requiredFields = ['proyecto_id', 'projectManager', 'startDate', 'endDate'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            die("Error: Por favor, completa todos los campos obligatorios.");
        }
    }

    $proyecto_id = $_POST['proyecto_id'];
    $responsable = $_POST['projectManager'];
    $fechaInicio = $_POST['startDate'];
    $fechaFin = $_POST['endDate'];
    $user_id = $_SESSION['user_id'];

    // Campos opcionales
    $waterUsage = $_POST['waterUsage'] ?? null;
    $waterCost = $_POST['waterCost'] ?? null;
    $energyUsage = $_POST['energyUsage'] ?? null;
    $energyCost = $_POST['energyCost'] ?? null;
    $operationalExpenses = $_POST['operationalExpenses'] ?? null;
    $budget = $_POST['budget'] ?? null;
    $budgetVariance = $_POST['budgetVariance'] ?? null;
    $observations = $_POST['observations'] ?? null;

    // Insertar el reporte en la base de datos
    $sql = "INSERT INTO reportes (proyecto_id, user_id, responsable, fecha_inicio, fecha_fin, consumo_agua, costo_agua, consumo_energia, costo_energia, gastos_operativos, presupuesto_total, variacion_presupuesto, observaciones)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "iissssdddddss",
        $proyecto_id,
        $user_id,
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
        // Redirigir a success.php si la inserción fue exitosa
        header("Location: success.php");
        exit();
    } else {
        // Manejar errores en la ejecución
        echo "Error al guardar el reporte: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Método no permitido.";
    exit();
}
