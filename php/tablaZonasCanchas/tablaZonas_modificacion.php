<?php
session_start();
require_once('../../config/database/conexion.php');
require_once('../../config/root_path.php');
$id = $_GET['id_zona'];
$id_sucursal = isset($_GET['id_sucursal']) ? $_GET['id_sucursal'] : die("no hay id de sucursal :(");;

$sqlEstado = "SELECT
					id_estado_zona,
					descripcion_estado_zona
				FROM
					estado_zona
				WHERE estado IN (1)";

$sqlTerreno = "SELECT id_tipo_terreno, descripcion_tipo_terreno
                FROM tipo_terreno WHERE estado IN(1)";

$sqlFutbol = "SELECT id_formato_deporte, descripcion_formato_deporte
                FROM formato_deporte WHERE estado IN(1)";

$sqlSucursal = "SELECT
                    id_sucursal,
                    descripcion_sucursal
                FROM
                    sucursal
                WHERE estado IN (1)";



$registrosEstado    = $conexion->query($sqlEstado);
$registrosComplejo    = $conexion->query($sqlSucursal);
$registrosTerreno  = $conexion->query($sqlTerreno);
$registrosFutbol  = $conexion->query($sqlFutbol);

$sql = "SELECT 	
						id_zona,
						descripcion_zona,
						descripcion_tipo_terreno,
						descripcion_formato_deporte,
						descripcion_sucursal,
						rela_tipo_terreno,
						rela_formato_deporte,
						rela_estado_zona,
						rela_sucursal						
					FROM
						zona
					JOIN
						servicio
					ON
						zona.rela_servicio = servicio.id_servicio
                    JOIN 
                    	sucursal
					ON 
						zona.rela_sucursal = sucursal.id_sucursal
					JOIN 
						formato_deporte
					ON 
						zona.rela_formato_deporte = formato_deporte.id_formato_deporte
					JOIN
						tipo_terreno
					ON
						zona.rela_tipo_terreno = tipo_terreno.id_tipo_terreno
					WHERE
						descripcion_servicio LIKE 'cancha'
					AND
						id_zona = $id
					AND
						zona.estado IN(1)";

$registros = $conexion->query($sql);

foreach ($registros as $reg) {

    $id         = $reg['id_zona'];
    $zona         = $reg['descripcion_zona'];
    $terreno     = $reg['rela_tipo_terreno'];
    $tipoFutbol = $reg['rela_formato_deporte'];
    $estado     = $reg['rela_estado_zona'];
    $sucursal    = $reg['rela_sucursal'];
}

if (isset($_POST['modificacion'])) {
    $zona = $_POST['descripcion'];
    $terreno = $_POST['terreno'];
    $tipoFutbol = $_POST['tipo_futbol'];
    $estado = $_POST['estado'];
    $sucursal = $id_sucursal;
    $sql = "UPDATE
				zona
			SET 
				descripcion_zona = '$zona',
				rela_tipo_terreno = '$terreno',
				rela_formato_deporte = '$tipoFutbol',
				rela_estado_zona = $estado,
				rela_sucursal = $sucursal,
				rela_servicio = 1
			WHERE
				id_zona = $id";
    if ($conexion->query($sql)) {
        header("Location: tablaZonas.php?id_sucursal=$id_sucursal");
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MODIFICACION ZONA</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/aside.css'; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/header.css' ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/tablaZonas.css">
</head>

<body>
    <?php include(RUTA. "includes/header.php"); ?>

    <?php include(RUTA."includes/menu_aside.php") ?>

    <script src="js/jquery-3.7.1.min.js"></script>
    <div class="containerEmpleado">
        <h1>Modulo de Modificacion de Zona</h1>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?id_zona=' . $id . '&id_sucursal=' . $id_sucursal; ?>" method="post" onsubmit="return confirmModification();">
            <label for="descripcion">Código:</label>
            <input type="text" id="descripcion" name="descripcion" value="" placeholder="cancha número 1" required>
            <p id="error_descripcion" style="color: red; display: none;">Solo se permiten letras, números y espacios.</p>

            <label for="terreno">Terreno:</label>
            <select id="terreno" name="terreno" required>
                <option value="" disabled selected>Seleccione un Terreno...</option>
                <?php foreach ($registrosTerreno as $reg) : ?>
                    <option value="<?= $reg['id_tipo_terreno']; ?>"
                        <?php if ($terreno == $reg['id_tipo_terreno']) {
                            echo 'selected';
                        } ?>>
                        <?= $reg['descripcion_tipo_terreno'] ?></option>
                <?php endforeach; ?>

            </select>

            <label for="tipo_futbol">Tipo de Fútbol:</label>
            <select id="tipo_futbol" name="tipo_futbol" required>
                <option value="" disabled selected>Seleccione una categoria...</option>
                <?php foreach ($registrosFutbol as $reg) : ?>
                    <option value="<?= $reg['id_formato_deporte'];  ?>"
                        <?php if ($reg['id_formato_deporte'] == $tipoFutbol) {
                            echo 'selected';
                        } ?>>
                        <?= $reg['descripcion_formato_deporte'] ?>

                    </option>
                <?php endforeach; ?>
            </select>

            <label for="estado">Estado:</label>
            <select id="estado" name="estado" required>
                <option value="" disabled selected>Seleccione un estado...</option>
                <?php foreach ($registrosEstado as $reg) : ?>
                    <option value="<?php echo $reg['id_estado_zona']; ?>" <?php if ($estado == $reg['id_estado_zona']) {
                                                                                echo 'selected';
                                                                            } ?>>
                        <?php echo $reg['descripcion_estado_zona']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" name="modificacion">Enviar</button>
        </form>
    </div>
    <script src="../../js/validarNomCancha.js"></script>
    <script>
        function confirmModification() {
            var respuesta = confirm("¿Estás seguro de que deseas aplicar las modificaciones?");
            return respuesta;
        }
    </script>
    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>

    <script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>

</body>

</html>