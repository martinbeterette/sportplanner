<?php  
	require_once("../../../config/root_path.php");
	require_once(RUTA . "config/database/conexion.php");
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('includes/filtrar_reporte.php');
	} else {
	    echo "Selecciona los filtros y haz clic en 'Filtrar' para generar el reporte.";
	}
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Reporte de reservas</title>
</head>
<body>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <!-- Filtro por tipo de fecha -->
    <label for="tipo_fecha">Tipo de Fecha:</label>
    <select name="tipo_fecha" id="tipo_fecha">
        <option value="fecha_reserva" <?php echo (isset($_POST['tipo_fecha']) && $_POST['tipo_fecha'] == 'fecha_reserva') ? 'selected' : ''; ?>>Fecha de Reserva</option>
        <option value="fecha_alta" <?php echo (isset($_POST['tipo_fecha']) && $_POST['tipo_fecha'] == 'fecha_alta') ? 'selected' : ''; ?>>Fecha de Alta</option>
    </select>

    <!-- Filtro por rango de fechas -->
    <label for="fecha_desde">Fecha Desde:</label>
    <input type="date" name="fecha_desde" id="fecha_desde" value="<?php echo isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : ''; ?>">

    <label for="fecha_hasta">Fecha Hasta:</label>
    <input type="date" name="fecha_hasta" id="fecha_hasta" value="<?php echo isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : ''; ?>">

    <!-- Filtro por estado de la reserva -->
    <label for="estado_reserva">Estado de la Reserva:</label>
    <select name="estado_reserva" id="estado_reserva">
        <option value="">Todos</option>
        <?php
        // Consulta para llenar el select con los estados
        $query_estados = "SELECT id_estado_zona, descripcion_estado_zona FROM estado_zona";
        $result_estados = $conexion->query($query_estados);
        while ($row = $result_estados->fetch_assoc()) {
            echo '<option value="' . $row['id_estado_zona'] . '"';
            echo (isset($_POST['estado_reserva']) && $_POST['estado_reserva'] == $row['id_estado_zona']) ? 'selected' : '';
            echo '>' . $row['descripcion_estado_zona'] . '</option>';
        }
        ?>
    </select>

    <button type="submit">Filtrar</button>
</form>

</body>
</html>