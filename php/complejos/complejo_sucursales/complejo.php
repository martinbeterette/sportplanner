<?php
//ACA SOLO LLEGA UN PERFIL PROPIETARIO SINO NO SE PUEDE ACCEDER
//LA PAGINA NO TIRA NADA SI NO SOS PROPIETARIO CON LA CUENTA ACTUAL PERO AUN ASI NO SE DEBERIA ENTRAR
session_start();
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");



$sql = false;

//si es propietario
if ($_SESSION['id_perfil'] == 23) {
    //obtenemos los parametros
    $id_persona = $_SESSION['id_persona'];
    $id_usuario = $_SESSION['id_usuario'];
    $id_complejo = obtenerComplejoPorPersona($id_persona, $id_usuario);
    $complejo = $id_complejo['rela_complejo'];
}

// Consulta SQL para obtener los datos del complejo y sus sucursales
$sql = "
    SELECT sucursal.id_sucursal, sucursal.descripcion_sucursal, sucursal.fecha_de_creacion,
           asignacion_sucursal_domicilio.id_asignacion_sucursal_domicilio, sucursal.rela_complejo,asignacion_sucursal_domicilio.direccion,
           barrio.id_barrio, barrio.descripcion_barrio
    FROM sucursal
    JOIN asignacion_sucursal_domicilio ON asignacion_sucursal_domicilio.rela_sucursal = sucursal.id_sucursal
    JOIN barrio ON barrio.id_barrio = asignacion_sucursal_domicilio.rela_barrio
    WHERE sucursal.rela_complejo = :id_complejo
    AND sucursal.estado IN(1)
";
if($id_complejo){

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id_complejo' => $id_complejo['rela_complejo']]);
    $sucursales = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header("Location: ". BASE_URL . "index2.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Sucursales</title>
    <link rel="stylesheet" href="css/complejo.css">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css"; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css"; ?>">
</head>
<body>

<?php include(RUTA."includes/header.php"); ?>
<?php include(RUTA."includes/menu_aside.php"); ?>

<div class="header">
    <?php if($id_complejo): ?>
        <h1><?php echo $id_complejo['descripcion_complejo']; ?></h1>
    <?php endif; ?>
</div>

<div class="container">
    <div class="complejo-info">
        <?php if($id_complejo){ ?>
            <h2>Información del Complejo Deportivo</h2>
            <p>Nombre del Complejo: <?php echo $id_complejo['descripcion_complejo']; ?>F</p>
            <p>Fecha de Creación: <?php echo $id_complejo['fecha_fundacion']; ?></p>
        <?php } else { ?>
            <h2>No posee un complejo</h2>
        <?php } ?>
    </div>

    <div class="sucursal-container">
        <?php if(isset($sucursales)) : ?>
            <?php foreach ($sucursales as $sucursal): ?>
                <div class="sucursal-card">
                    <h3><?= htmlspecialchars($sucursal['descripcion_sucursal']) ?></h3>
                    <p><strong>Fecha de creación:</strong> <?= htmlspecialchars($sucursal['fecha_de_creacion']) ?></p>
                    <p><strong>Dirección:</strong> <?= htmlspecialchars($sucursal['direccion']) ?></p>
                    <p><strong>Barrio:</strong> <?= htmlspecialchars($sucursal['descripcion_barrio']) ?></p>
                    <div class="buttons">
                        <button class="btn btn-ver"
                            data-reg="<?= htmlspecialchars(json_encode($sucursal), ENT_QUOTES, 'UTF-8') ?>"
                        >
                        Ver más</button>
                        <button class="btn btn-modificar" 
                            data-id="<?= $sucursal['id_sucursal'] ?>" 
                            data-id-complejo="<?= $complejo ?>"
                        >Modificar</button>
                        <button 
                            class="btn btn-eliminar" 
                            data-id="<?= $sucursal['id_sucursal'] ?>"
                            data-id-complejo="<?= $complejo ?>"
                        >Eliminar</button>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php if($id_complejo) { ?>
        <a href="agregar_sucursal.php?id_complejo=<?= $complejo ?>" class="btn btn-agregar">Agregar Nueva Sucursal</a>
    <?php } ?>
</div>

<div class="footer">
    <p>&copy; 2024 Complejo Deportivo YPF. Todos los derechos reservados.</p>
</div>
<script src="<?php echo BASE_URL. "libs/jquery-3.7.1.min.js" ?>"></script>
<script src="<?php echo BASE_URL. "libs/sweetalert2.all.min.js" ?>"></script>
<script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
<script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
<script src="js/eliminar.js"></script>
<script src="js/ver_mas.js"></script>
<script src="js/modificar.js"></script>
</body>
</html>

<?php  

function obtenerComplejoPorPersona($id_persona, $id_usuario) {
    global $conexion;

    $sql_sucursal_empleado = "
        SELECT apc.rela_complejo, descripcion_complejo, complejo.fecha_fundacion
            FROM asignacion_persona_complejo apc
            JOIN complejo on apc.rela_complejo = id_complejo
            WHERE apc.rela_persona = ? AND apc.rela_usuario = ?";

    $stmt_obtener_complejo = $conexion->prepare($sql_sucursal_empleado);
    $stmt_obtener_complejo->bind_param("ii", $id_persona, $id_usuario);

    if ($stmt_obtener_complejo->execute()) {
        $datos_complejo = $stmt_obtener_complejo->get_result()->fetch_assoc() ?? false;
        return $datos_complejo;
    }
    return false;
}

?>
