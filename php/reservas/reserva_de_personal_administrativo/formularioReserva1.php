<?php 
    session_start();
    require_once("../../../config/root_path.php");
    require_once(RUTA . "config/database/conexion.php");
    if($_SESSION['id_perfil'] == 3) {
        $id_persona = $_SESSION['id_persona'];
        $id_usuario = $_SESSION['id_usuario'];
        $id_sucursal = obtenerComplejoPorPersona($id_persona, $id_usuario);

    }

    if($_SESSION['id_perfil'] == 23) {
        $id_sucursal = $_GET['id_sucursal'] ?? header("Location: /includes/seleccionar_sucursal.php");
        if(!$id_sucursal) { 
            header("Location: includes/seleccionar_sucursal.php");
        }
    }

    $registros_cancha = $conexion->query("SELECT * FROM zona WHERE rela_sucursal = $id_sucursal AND estado=1");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Reserva</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css" ?>">
</head>
<body>

<?php include(RUTA . "includes/header.php"); ?>
<?php include(RUTA . "includes/menu_aside.php"); ?>


<div class="container">
    <!-- Tabla de Personas -->
    <div class="table-container">
        <h2>Personas</h2>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Buscar por nombre, apellido o documento">
            <button id="buscar">Buscar</button>
        </div>
        <div id="tabla-personas"></div>
        <div id="pagination"></div>
    </div>

    <!-- Lista de Canchas -->
    <div class="list-container">
        <h2>Canchas</h2>
        <ul style="position: relative">
            <?php foreach ($registros_cancha as $reg) { ?>
                <li data-value="<?php echo $reg['id_zona']; ?>">
                    <?php echo $reg['descripcion_zona']; ?> 
                    <button class="seleccionar-cancha">Seleccionar</button>
                </li>

            <?php } ?>
        </ul>

        <!-- Selector de Fecha -->
        <label for="fecha_reserva"><strong>Seleccione una fecha:</strong></label>
        <input name="fecha_reserva" type="text" id="datepicker" placeholder="Selecciona una fecha">
    </div>
</div>
<div class="boton-reservar" align="center">
    <button 
        id="reservar"
        id_zona=""
        id_persona=""
        fecha_reserva=""
    >Buscar Reserva</button>
</div>

<script src="<?php echo BASE_URL. "libs/jquery-3.7.1.min.js" ?>"></script>
<script src="<?php echo BASE_URL. "js/header.js" ?>"></script>
<script src="<?php echo BASE_URL. "js/aside.js" ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="js/tablaYPaginacion.js"></script>
<script>
    // Configuración de Flatpickr
    flatpickr("#datepicker", {
        dateFormat: "Y-m-d",
        minDate: "today",
        maxDate: new Date().fp_incr(7), // Hasta 7 días adelante
        defaultDate: "today" // Esto selecciona la fecha de hoy por defecto
    });

    $('.seleccionar-cancha').click(function () {
        $('li').css('background-color','');
        let id_zona = $(this).parent().data('value');
        $(this).parent().css('background-color','#0056b3');
        $('#reservar').attr('id_zona',id_zona);
    });

    $(document).on('click', '.seleccionar-persona',function () {
        $('tr').css('background-color', '')
        // Obtener el valor de data-value
        let id_persona = $(this).data('value');
        let tr_seleccionado = $(this).closest('tr');
        tr_seleccionado.css('background-color', '#e0e0e0')
        // Insertar ese valor en el atributo id_persona del elemento #reservar
        $('#reservar').attr('id_persona', id_persona);
    });

    $('#reservar').on('click',function() {
        let id_persona          = $(this).attr('id_persona');
        let id_zona             = $(this).attr('id_zona');
        let fecha_reserva       = $('#datepicker').val();
        alert(`id_persona=${id_persona}&cancha=${id_zona}&fecha_reserva=${fecha_reserva}`);
        window.location.href = `formularioReserva2.php?persona=${id_persona}&cancha=${id_zona}&fecha_reserva=${fecha_reserva}`;
    });
</script>

</body>
</html>

<?php  
function obtenerComplejoPorPersona($id_persona, $id_usuario) {
    global $conexion;

    $sql_sucursal_empleado = "
        SELECT s.id_sucursal 
        FROM empleado e
        JOIN sucursal s ON e.rela_sucursal = s.id_sucursal
        JOIN persona p ON e.rela_persona = p.id_persona
        JOIN contacto c ON p.id_persona = c.rela_persona
        JOIN usuarios u ON c.id_contacto = u.rela_contacto AND u.id_usuario = ?
        WHERE e.rela_persona = ?;";

    $stmt_obtener_complejo = $conexion->prepare($sql_sucursal_empleado);
    $stmt_obtener_complejo->bind_param("ii", $id_usuario, $id_persona);

    if ($stmt_obtener_complejo->execute()) {
        $datos_complejo = $stmt_obtener_complejo->get_result()->fetch_assoc()['id_sucursal'] ?? false;
        return $datos_complejo;
    }
    return false;
}

?>