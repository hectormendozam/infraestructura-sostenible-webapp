<?php
session_start();

// Verificar si hay objetivos guardados en la sesión
if (!isset($_SESSION['objetivos']) || empty($_SESSION['objetivos'])) {
    echo json_encode([]);
    exit();
}

// Simular datos para los objetivos seleccionados
$objetivosSeleccionados = $_SESSION['objetivos'];
$objetivosData = [];

foreach ($objetivosSeleccionados as $objetivo) {
    $objetivosData[] = [
        'objetivo' => ucfirst($objetivo),
        'completados' => rand(0, 10), // Datos simulados
        'pendientes' => rand(0, 10)  // Datos simulados
    ];
}

echo json_encode($objetivosData);
?>
