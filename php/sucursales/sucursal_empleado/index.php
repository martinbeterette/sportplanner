<?php

// ESTE ES PARA EL EMPLEADO
session_start();
require_once("includes/functions.php");
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");



$id_sucursal = false;
if ($_SESSION['id_perfil'] == 3) {
    $id_persona = $_SESSION['id_persona'];
    $id_usuario = $_SESSION['id_usuario'];
    $id_sucursal = obtenerComplejoPorPersona($id_persona, $id_usuario);
}

if(!$id_sucursal) {
    header("Location: " . BASE_URL . "errors/error403.php?no_tiene_acceso");
}

// Consulta para obtener la información de la sucursal
$sql_sucursal = "
    SELECT 
        s.id_sucursal,
        s.descripcion_sucursal,
        s.fecha_de_creacion,
        asd.direccion,
        b.descripcion_barrio
    FROM sucursal s
    JOIN asignacion_sucursal_domicilio asd ON s.id_sucursal = asd.rela_sucursal
    JOIN barrio b ON asd.rela_barrio = b.id_barrio
    WHERE s.id_sucursal = ?";
$stmt = $conexion->prepare($sql_sucursal);
$stmt->bind_param("i", $id_sucursal);
$stmt->execute();
$sucursal = $stmt->get_result()->fetch_assoc();

if (!$sucursal) {
    echo "No se encontró la sucursal."; 
    die;
}

// Consulta para listar las canchas asociadas a la sucursal
$sql_canchas = "
    SELECT 
        z.id_zona,
        z.descripcion_zona,
        estado_zona.descripcion_estado_zona,
        fd.descripcion_formato_deporte
    FROM zona z
    JOIN formato_deporte fd ON z.rela_formato_deporte = fd.id_formato_deporte
    JOIN estado_zona ON rela_estado_zona = id_estado_zona
    WHERE z.rela_sucursal = ? AND z.estado = 1";
$stmt_canchas = $conexion->prepare($sql_canchas);
$stmt_canchas->bind_param("i", $id_sucursal);
$stmt_canchas->execute();
$canchas = $stmt_canchas->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucursal: <?php echo htmlspecialchars($sucursal['descripcion_sucursal']); ?></title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css" ?>">
</head>
<body>
    <?php include(RUTA."includes/header.php"); ?>
    <?php include(RUTA."includes/menu_aside.php"); ?>

    <div class="header">
	    <h1>Sucursal: <?php echo htmlspecialchars($sucursal['descripcion_sucursal']); ?></h1>
	</div>

    

    <div class="sucursal-info">
	    <div class="info-left">
	        <p><strong>Dirección:</strong> <?php echo htmlspecialchars($sucursal['direccion']); ?></p>
	        <p><strong>Barrio:</strong> <?php echo htmlspecialchars($sucursal['descripcion_barrio']); ?></p>
	        <p><strong>Fecha de Creación:</strong> <?php echo htmlspecialchars($sucursal['fecha_de_creacion']); ?></p>
	    </div>
	    <div class="gestion-buttons">
	        <a href="<?php echo BASE_URL."php/tarifas/tarifa_empleado/tabla_tarifa.php"; ?>" class="btn btn-gestion">Gestión de tarifas</a>
	        <a href="<?php echo BASE_URL . "php/socio/gestion_socios/index.php" ?>" class="btn btn-gestion">Gestión de socios</a>
	        <a href="<?php echo BASE_URL . "php/tablaEmpleados/gestion_empleado/tablaEmpleados.php" ?>" class="btn btn-gestion">Gestión de empleados</a>
	    </div>
	</div>

    <div class="canchas-container">
        <h2>Listado de canchas</h2>
        <?php if ($canchas->num_rows > 0): ?>
            <div class="canchas-grid">
                <?php foreach ($canchas as $cancha): ?>
                    <div class="cancha-card">
                        <h3><?php echo htmlspecialchars($cancha['descripcion_zona']); ?></h3>
                        <p><strong>Tipo:</strong> <?php echo htmlspecialchars($cancha['descripcion_formato_deporte']); ?></p>
                        <p><strong>Estado:</strong> <?php echo $cancha['descripcion_estado_zona']; ?></p>
                        <button 
                            class="btn btn-reservar" 
                            onclick="location.href='modulo_reservas.php?id_zona=<?php echo $cancha['id_zona']; ?>&fecha=<?php echo date('Y-m-d'); ?>'">
                            Reservar
                        </button>
                        <button 
                            class="btn btn-modificar" 
                            onclick="location.href='modificar_cancha.php?id_zona=<?php echo $cancha['id_zona']; ?>'">
                            Modificar
                        </button>
                        <button 
                            class="btn btn-eliminar" 
                            onclick="if(confirm('¿Desea eliminar esta cancha?')) location.href='eliminar_cancha.php?id_zona=<?php echo $cancha['id_zona']; ?>';">
                            Eliminar
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No hay canchas registradas en esta sucursal.</p>
        <?php endif; ?>

        <!-- Botón para agregar nueva cancha -->
        <div class="add-cancha">
            <button class="btn btn-agregar" onclick="location.href='agregar_cancha.php?id_sucursal=<?php echo $id_sucursal; ?>'">Agregar Cancha</button>
        </div>
    </div>
</body>
</html>

