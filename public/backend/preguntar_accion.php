<?php
// Recibir los objetivos seleccionados desde el formulario previo
$objetivosSeleccionados = isset($_POST['objetivos']) ? $_POST['objetivos'] : [];

if (empty($objetivosSeleccionados)) {
    echo "No se seleccionaron objetivos. <a href='objectives.html'>Volver</a>";
    exit;
}

// Serializar los objetivos para enviarlos a la siguiente página
$objetivosSerializados = urlencode(json_encode($objetivosSeleccionados));
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
                <input type="hidden" name="objetivos" value="<?php echo $objetivosSerializados; ?>">
                <button type="submit" class="btn btn-primary btn-lg">Generar Reporte</button>
            </form>
            <!-- Botón para volver a la página de objetivos -->
            <a href="objectives.html" class="btn btn-secondary btn-lg">Volver a Objetivos</a>
        </div>
    </div>
</body>
</html>
