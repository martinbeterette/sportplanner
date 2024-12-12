<?php
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
session_start();

if ($_SESSION['id_perfil'] == 23) {

    if (isset($_GET['id_sucursal'])) {
        $id_sucursal = $_GET['id_sucursal'];
        $id_usuario = $_SESSION['id_usuario'];

        //obtenemos las sucursales del propietario y las validamos por la seleccionada
        //es decir, si puede gestionar la que esta en la url
        $sucursales = obtenerSucursalesDelPropietario($id_usuario);
        if ($sucursales) {
            $array_sucursales = [];
            foreach ($sucursales as $reg) {
                $array_sucursales[] = $reg['id_sucursal'];
            }
        }

        // if (!in_array($id_sucursal, $array_sucursales)) {
        //     header("Location: includes/seleccionar_sucursal.php");
        //     exit();
        // }
    } else {
        header("Location: includes/seleccionar_sucursal.php");
        exit();
    }
}

if ($_SESSION['id_perfil'] == 3) {
    $id_persona = $_SESSION['id_persona'];
    $id_usuario = $_SESSION['id_usuario'];
    $id_sucursal = obtenerComplejoPorPersona($id_persona, $id_usuario);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <div class="filtros">
            <label for="desde">Fecha Desde</label>
            <input type="date" name="fecha_desde" id="fecha_desde">
            <label for="hasta">Fecha Hasta</label>
            <input type="date" name="fecha_hasta" id="fecha_hasta">
            <button id="filtro">filtrar</button>
        </div>
        <div class="tabla-container" id="tabla-container"></div>
        <div class="paginacion-container" id="paginacion-container"></div>
    </div>

    <script src="../../libs/jquery-3.7.1.min.js"></script>
    <script src="../../libs/sweetalert2.all.min.js"></script>
    <script>
        const id_sucursal = <?php echo $id_sucursal ?>;
        $(document).ready(function() {

            function cargarTabla(id_sucursal, pagina = 1, fecha_desde = '', fecha_hasta = '') {
                $.ajax({
                    url: 'ajax/obtener_reservas.php',
                    type: 'GET',
                    data: {
                        fecha_desde: fecha_desde,
                        fecha_hasta: fecha_hasta,
                        pagina: pagina,
                        id_sucursal: id_sucursal
                    },
                    dataType: 'json',
                    success: function(data) {
                        // Actualizar el contenedor de la tabla con el HTML generado
                        $('#tabla-container').html(data.tabla);
                        // Actualizar la paginación
                        actualizarPaginacion(data.total_pages, data.current_page);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud AJAX: ", status, error);
                    }
                });
            }

            // Función para actualizar los controles de paginación
            function actualizarPaginacion(total_pages, current_page) {
                var paginacionHTML = '';

                // Generar botones de paginación
                for (var i = 1; i <= total_pages; i++) {
                    if (i === current_page) {
                        paginacionHTML += '<span class="pagina-activa">' + i + '</span>';
                    } else {
                        paginacionHTML += '<button class="pagina-boton" data-page="' + i + '">' + i + '</button>';
                    }
                }

                $('#paginacion-container').html(paginacionHTML);
            }

            // Cargar la tabla inicialmente sin filtro
            cargarTabla(id_sucursal);

            // Evento de búsqueda
            $(document).on('click', '#filtro', function() {
                var fecha_desde = $('#fecha_desde').val();
                var fecha_hasta = $('#fecha_hasta').val();
                cargarTabla(id_sucursal, 1, fecha_desde, fecha_hasta); //llamar a la funcion con el termino de busqueda
            });

            // Evento para cambiar de página
            $(document).on('click', '.pagina-boton', function() {
                var fecha_desde = $('#fecha_desde').val();
                var fecha_hasta = $('#fecha_hasta').val();
                var page = $(this).data('page');
                cargarTabla(id_sucursal, page, fecha_desde, fecha_hasta);
            });

        }); // Cierre del DOCUMENT READY
    </script>
</body>

</html>

<?php
function obtenerSucursalesDelPropietario($id_usuario)
{
    global $conexion;
    $sql = "
                    SELECT id_sucursal
                    FROM sucursal s JOIN complejo ON id_complejo = s.rela_complejo
                    JOIN asignacion_persona_complejo apc ON id_complejo = apc.rela_complejo
                    WHERE apc.rela_usuario = ?
                ";

    $stmt_sucursales_propietario = $conexion->prepare($sql);
    $stmt_sucursales_propietario->bind_param("i", $id_usuario);
    if ($stmt_sucursales_propietario->execute()) {
        $registros = $stmt_sucursales_propietario->get_result();
        return $registros;
    }
    return false;
}
?>