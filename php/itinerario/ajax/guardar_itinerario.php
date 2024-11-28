<?php
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

// Verificar que se reciban los datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $sucursal_id = isset($_POST['sucursal_id']) ? (int)$_POST['sucursal_id'] : 0;
    $dias_habiles = isset($_POST['dias_habiles']) ? $_POST['dias_habiles'] : [];
    $hora_apertura = isset($_POST['hora_apertura']) ? $_POST['hora_apertura'] : '';
    $hora_cierre = isset($_POST['hora_cierre']) ? $_POST['hora_cierre'] : '';

    // Validaciones básicas
    if ($sucursal_id <= 0 || empty($dias_habiles) || empty($hora_apertura) || empty($hora_cierre)) {
        echo "Error: Por favor, complete todos los campos correctamente.";
        exit;
    }

    // Eliminar el itinerario existente para esta sucursal
    $stmt_delete = $conn->prepare("DELETE FROM itinerario WHERE rela_sucursal = ?");
    $stmt_delete->bind_param("i", $sucursal_id);
    $stmt_delete->execute();
    $stmt_delete->close();

    // Insertar los nuevos días y horarios en la tabla itinerario
    $stmt_insert = $conn->prepare("INSERT INTO itinerario (rela_sucursal, dia_semana, hora_apertura, hora_cierre) VALUES (?, ?, ?, ?)");

    foreach ($dias_habiles as $dia) {
        $stmt_insert->bind_param("isss", $sucursal_id, $dia, $hora_apertura, $hora_cierre);
        $stmt_insert->execute();
    }

    $stmt_insert->close();
    $conn->close();

    echo "Itinerario guardado correctamente.";
} else {
    echo "Error: No se recibieron datos válidos.";
}
?>
