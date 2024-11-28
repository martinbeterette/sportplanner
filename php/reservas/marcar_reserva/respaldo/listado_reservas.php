<?php
session_start();
require_once("../../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");
require_once("includes/functions.php");


if ($_SESSION['id_perfil'] == 3) {
    $id_persona = $_SESSION['id_persona'];
    $id_usuario = $_SESSION['id_usuario'];
    $id_sucursal = obtenerSucursalDelEmpleado($id_persona, $id_usuario);
}

if($_SESSION['id_perfil'] == 23) {

    $id_sucursal = isset($_GET['id_sucursal']) ? filter_input(INPUT_GET, 'id_sucursal',FILTER_SANITIZE_NUMBER_INT) : header("Location: includes/elegir_sucursal.php");
}

$id_persona = isset($_SESSION['id_persona']) ? $_SESSION['id_persona'] : header("Location: " . BASE_URL . "index2.php");
$esEmpleado = esEmpleadoDeLaSucursal($id_persona, $id_sucursal, $conexion);

if(!$esEmpleado) {header("Location: " . BASE_URL ."index2.php");}


$consulta_reservas = 
    "SELECT 
            r.id_reserva, 
            r.rela_persona, 
            r.rela_zona, 
            z.descripcion_zona,
            r.rela_estado_reserva,
            p.nombre,
            p.apellido,
            c.fecha_alta, 
            c.metodo_pago, 
            ec.descripcion_estado_control,
            c.monto_base, 
            c.monto_final, 
            c.rela_tarifa, 
            c.entrada  , 
            c.salida  
        FROM 
            reserva r
        JOIN persona p ON r.rela_persona = p.id_persona
        JOIN 
            zona z ON z.id_zona = r.rela_zona
        JOIN 
            sucursal s ON z.rela_sucursal = s.id_sucursal
        LEFT JOIN 
            control c ON c.rela_reserva = r.id_reserva
        LEFT JOIN estado_control ec ON ec.id_estado_control = c.rela_estado_control
        WHERE 
            s.id_sucursal = {$id_sucursal}
";

$reservas_hechas = $conexion->query($consulta_reservas);
?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Reservas</title>
    <link rel="stylesheet" href="css/listado_reservas.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>

<div class="contenedor-listado">
    <h1 class="titulo-listado">Listado de Reservas</h1>

    <!-- Contenedor del buscador -->
    <input type="text" id="buscador" placeholder="Buscar nombre, apellido..." />

    <!-- Contenedor del filtro por fecha -->
    <input type="text" id="filtro-fecha" placeholder="Selecciona una fecha" />
    <div id="tabla-container">
        <!-- Aquí se carga dinámicamente la tabla -->
    </div>

    <div id="paginacion-container">
        <!-- Aquí se carga dinámicamente la tabla -->
    </div>
</div>

<script src="<?php echo BASE_URL. "libs/sweetalert2.all.min.js" ?>"></script>
<script src="<?php echo BASE_URL. "libs/jquery-3.7.1.min.js" ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<?php if(isset($_GET['no_hay_entrada'])) { ?>
    <script>Swal.fire("Error","Primero debe marcar una llegada", "warning");</script>
<?php } ?>
<script>
    let respuesta = false;
    if (respuesta) {
        Swal.fire({
            icon: 'success',
            title: `Llegada marcada para la reserva`,
            timer: 1500,
            showConfirmButton: false
        });
    }
</script>
<script>var pagina_actual = <?php echo $_GET['pagina_actual'] ?? 1; ?></script>
<script src="js/habilitaciones_botones.js"></script>
<script src="js/tablaYPaginado.js"></script>
</body>
</html>
