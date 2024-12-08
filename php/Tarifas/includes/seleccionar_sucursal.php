<?php  
	session_start();

	require_once("../../../config/root_path.php");
	require_once(RUTA . 'config/database/db_functions.php');
	$id_usuario = $_SESSION['id_usuario'];
	$sucursales = obtenerSucursalesDelPropietario($id_usuario);
	if($sucursales) {
		foreach ($sucursales as $reg) {
			$array_sucursales[] = $reg['id_sucursal'];
		}
		$array_sucursales_imploded = implode(",", $array_sucursales);
	}

	// Consulta
	$sql = "SELECT sucursal.id_sucursal, sucursal.descripcion_sucursal, sucursal.fecha_de_creacion,
	               asignacion_sucursal_domicilio.direccion, barrio.descripcion_barrio
	        FROM sucursal
	        JOIN asignacion_sucursal_domicilio ON asignacion_sucursal_domicilio.rela_sucursal = sucursal.id_sucursal
	        JOIN barrio ON barrio.id_barrio = asignacion_sucursal_domicilio.rela_barrio
	        WHERE sucursal.id_sucursal IN($array_sucursales_imploded)
	          AND sucursal.estado IN(1)";

	$resultado_sucursales = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Sucursales</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: 20px auto;
        }
        .sucursal-card {
            background: #fff;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .sucursal-card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .sucursal-card h3 {
            margin: 0;
            color: #333;
        }
        .sucursal-card p {
            margin: 5px 0;
            color: #555;
        }
    </style>
    <script>
        function irASucursal(idSucursal) {
            window.location.href = `../tabla_tarifa.php?id_sucursal=${idSucursal}`;
        }
    </script>
    <link rel="stylesheet" href="<?php echo BASE_URL. "css/aside.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL. "css/header.css" ?>">
</head>
<body>
	<?php include(RUTA . "includes/header.php"); ?>
	<?php include(RUTA . "includes/menu_aside.php"); ?>
    <div class="header">
        <h1>Listado de Sucursales</h1>
    </div>
    <div class="container">
        <?php if ($resultado_sucursales && $resultado_sucursales->num_rows > 0): ?>
            <?php while ($row = $resultado_sucursales->fetch_assoc()): ?>
                <div class="sucursal-card" onclick="irASucursal(<?= $row['id_sucursal'] ?>)">
                    <h3><?= htmlspecialchars($row['descripcion_sucursal']) ?></h3>
                    <p>Dirección: <?= htmlspecialchars($row['direccion']) ?></p>
                    <p>Barrio: <?= htmlspecialchars($row['descripcion_barrio']) ?></p>
                    <p>Fecha de Creación: <?= htmlspecialchars($row['fecha_de_creacion']) ?></p>
                    <!-- ID impreso en el HTML -->
                    <p class="hidden-id" style="display: none;"><?= $row['id_sucursal'] ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No se encontraron sucursales.</p>
        <?php endif; ?>
    </div>
    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/header.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js" ?>"></script>
</body>
</html>

<?php

function obtenerSucursalesDelPropietario($id_usuario) {
    global $conexion;
    $sql = "
        SELECT id_sucursal
        FROM sucursal s JOIN complejo ON id_complejo = s.rela_complejo
        JOIN asignacion_persona_complejo apc ON id_complejo = apc.rela_complejo
        WHERE apc.rela_usuario = ?
    ";

    $stmt_sucursales_propietario = $conexion->prepare($sql);
    $stmt_sucursales_propietario->bind_param("i",$id_usuario);
    if($stmt_sucursales_propietario->execute()){
        $registros = $stmt_sucursales_propietario->get_result();
        return $registros;
    }
    return false;
}
?>