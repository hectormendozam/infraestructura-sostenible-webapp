<?php
// Iniciar sesión para guardar los objetivos
session_start();

// Recibir los objetivos seleccionados
$objetivosSeleccionados = isset($_POST['objetivos']) ? $_POST['objetivos'] : [];

if (empty($objetivosSeleccionados)) {
    echo "No se seleccionaron objetivos. <a href='../../src/screens/objectives.html'>Volver</a>";
    exit;
}

// Conectar a la base de datos
$conexion = new mysqli('localhost', 'root', '12345678', 'proyecto_db');
if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

// Insertar los objetivos en la tabla `objetivo`
foreach ($objetivosSeleccionados as $tipo) {
    // Validar si ya existe un objetivo activo del mismo tipo
    $consulta = $conexion->prepare("SELECT id FROM objetivo WHERE tipo = ? AND activo = 1");
    $consulta->bind_param('s', $tipo);
    $consulta->execute();
    $resultado = $consulta->get_result();

    if ($resultado->num_rows === 0) {
        // Insertar el nuevo objetivo con cantidad inicial en 0
        $insercion = $conexion->prepare("INSERT INTO objetivo (tipo, cantidad, activo, fecha_creacion) VALUES (?, 0, 1, NOW())");
        $insercion->bind_param('s', $tipo);
        $insercion->execute();
    }

    $consulta->close();
}

// Cerrar la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Acción</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5 text-center">
        <h3>¿Qué deseas hacer?</h3>
        <p class="my-3">Puedes generar un nuevo reporte en base a los objetivos seleccionados o regresar a la página de objetivos.</p>
        <div class="d-flex justify-content-center gap-3">
            <!-- Botón para generar un nuevo reporte -->
            <form action="ingresar_reporte.php" method="post">
                <button type="submit" class="btn btn-primary btn-lg">Generar Reporte</button>
            </form>
            <!-- Botón para volver a la página de objetivos -->
            <a href="../../src/screens/objectives.html" class="btn btn-secondary btn-lg">Volver a Objetivos</a>
        </div>
    </div>
</body>
</html>
