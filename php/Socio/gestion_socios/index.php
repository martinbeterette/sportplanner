<?php
session_start();
require_once("../../../config/root_path.php");
require_once("includes/functions.php");
require_once(RUTA . "config/database/conexion.php");

$id_persona = $_SESSION['id_persona'] ?? 0;
$id_sucursal = false;
$id_complejo = false;

if ($_SESSION['id_perfil'] == 3) {
    $id_persona = $_SESSION['id_persona'];
    $id_usuario = $_SESSION['id_usuario'];
    $id_sucursal = obtenerSucursalPorPersona($id_persona, $id_usuario);

    if (!$id_sucursal) {
        header("Location: " . BASE_URL . "index2.php");
    }
    //obtenemos el complejo del empleado
    $id_complejo = obtenerIdComplejoDelEmpleado($id_persona,$id_usuario); //de la persona en realidad
} 



/*if(!$id_complejo) {
    header("Location: " . BASE_URL . "errors/error403.php?no_tiene_acceso");
}*/

$registros_socios = obtenerSocios($id_complejo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Socios</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/header.css' ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . 'css/aside.css' ?>">
    <!-- <link rel="stylesheet" type="text/css" href="css/index.css"> -->
    <link rel="stylesheet" type="text/css" href="css/prueba.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.min.css">
</head>
<body>
    <?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php"); ?>
<div class="container">
    <h1 align="center">Listado de Socios</h1>
    <input type="text" id="buscador" placeholder="Nombre, Apellido, Documento">
    <div id="tabla-container"></div>
    <div id="paginacion-container"></div>

    <button class="btn-agregar" onclick="window.location.href='includes/agregar.php'">Agregar Socio</button>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.all.min.js"></script>
<script>let id_complejo = <?php echo $id_complejo; ?>;</script>
<script>
    $(document).ready(function() {
        $('.btn-ver-detalles').on('click', function() {
            // Obtenemos los datos del socio del atributo data-socio
            const socio = $(this).data('socio');

            // Creamos el contenido del modal con los datos del socio
            let contenidoModal = `
                <p><strong>Nombre:</strong> ${socio.nombre}</p>
                <p><strong>Apellido:</strong> ${socio.apellido}</p>
                <p><strong>Documento:</strong> ${socio.documento} (${socio.tipo_documento})</p>
                <p><strong>Fecha de Alta:</strong> ${socio.fecha_alta}</p>
                <p><strong>Fecha de Renovación:</strong> ${socio.fecha_renovacion}</p>
                <p><strong>Fecha de Expiración:</strong> ${socio.fecha_expiracion}</p>
                <p><strong>Membresía:</strong> ${socio.descripcion_membresia}</p>
                <p><strong>Descuento:</strong> ${socio.descuento}%</p>
                <p><strong>Precio:</strong> $${socio.precio}</p>
            `;

            // Mostramos el modal con SweetAlert2
            Swal.fire({
                title: 'Detalles del Socio',
                html: contenidoModal,
                icon: 'info',
                confirmButtonText: 'Cerrar'
            });
        });

    });
</script>
<script src="js/TablaYPaginado.js"></script>
</body>
</html>
