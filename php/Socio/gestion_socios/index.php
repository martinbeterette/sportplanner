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
        header("Location: " . BASE_URL . "index.php");
    }
    //obtenemos el complejo del empleado
    $id_complejo = obtenerIdComplejoDelEmpleado($id_persona, $id_usuario); //de la persona en realidad
}

if ($_SESSION['id_perfil'] == 23) {
    $id_persona = $_SESSION['id_persona'];
    $id_usuario = $_SESSION['id_usuario'];

    $id_complejo = obtenerComplejo($id_usuario);
    $id_complejo = $id_complejo->fetch_assoc()['rela_complejo'];
}


if (!$id_complejo) {
    header("Location: " . BASE_URL);
}

$registros_socios = obtenerSocios($id_complejo);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Listado de Socios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/footer.css" ?>">
    <link rel="stylesheet" type="text/css" href="css/prueba.css">
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

    <?php include(RUTA . "includes/footer.php") ?>

    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "libs/sweetalert2.all.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/header.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/terminoscondiciones.js" ?>"></script>

    <script>
        let id_complejo = <?php echo $id_complejo; ?>;
    </script>

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

<?php

function obtenerComplejo($id_usuario)
{
    global $conexion;
    $sql = "
        SELECT rela_complejo
        FROM asignacion_persona_complejo apc 
        WHERE apc.rela_usuario = ?
    ";

    $stmt_complejo_del_propietario = $conexion->prepare($sql);
    $stmt_complejo_del_propietario->bind_param("i", $id_usuario);
    if ($stmt_complejo_del_propietario->execute()) {
        $registros = $stmt_complejo_del_propietario->get_result();
        return $registros;
    }
    return false;
}
?>