<?php
// Habilitar la detección de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';
// Iniciar la sesión
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    // Si no está autenticado, redirigir al login
    header("Location: login.html");
    exit();
}

// Obtener los proyectos creados por el usuario
$user_id = $_SESSION['user_id'];
$sql = "SELECT id, nombre, descripcion FROM proyectos WHERE usuario_id = ? AND eliminado = 0";
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
    <title>Editar Proyectos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" href="../img/planeta.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@200..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
</head>
<body>
<div class="container my-5">
        <h3 class="text-center">Editar proyecto</h3>

        <!-- Mostrar mensajes de error o éxito -->
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="save_project.php" method="post">
            <!-- Selección de proyecto -->
            <div class="mb-3">
                <label for="proyecto" class="form-label">Selecciona un Proyecto</label>
                <select class="form-control" name="proyecto_id" id="proyecto" required>
                    <option value="">Selecciona un proyecto</option>
                    <?php foreach ($proyectos as $proyecto): ?>
                        <option value="<?= htmlspecialchars($proyecto['id']); ?>"
                                data-nombre="<?= htmlspecialchars($proyecto['nombre']); ?>"
                                data-descripcion="<?= htmlspecialchars($proyecto['descripcion']); ?>">
                            <?= htmlspecialchars($proyecto['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <fieldset>
                    <label for="name" class="form-label">Nombre del Proyecto</label>
                    <input class="form-control mb-3" type="text" id="name" name="name">

                    <label for="description" class="form-label">Descripción del Proyecto</label>
                    <textarea class="form-control mb-3" id="description" name="description"></textarea>
                </fieldset>                
            </div>
            <button class="btn btn-primary w-100" type="submit" id="botonCambios">Guardar Cambios</button><break><break>
        </form>
</div>
    
</body>
<script>
    document.getElementById('proyecto').addEventListener('change', function () {
        // Obtener el proyecto seleccionado
        const selectedOption = this.options[this.selectedIndex];

        // Obtener los atributos data-* de la opción seleccionada
        const nombre = selectedOption.getAttribute('data-nombre') || '';
        const descripcion = selectedOption.getAttribute('data-descripcion') || '';

        // Asignar los valores a los campos del formulario
        document.getElementById('name').value = nombre;
        document.getElementById('description').value = descripcion;
    });
</script>
</html>