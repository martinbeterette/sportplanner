<?php
session_start();
require_once('../../config/root_path.php');
require_once(RUTA . "config/database/conexion.php");
if ($_SESSION['id_perfil'] == 3) {
    $id_persona     = $_SESSION['id_persona'];
    $id_usuario     = $_SESSION['id_usuario'];
    $id_sucursal    = obtenerSucursal($id_persona, $id_usuario);
}

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

        if (!in_array($id_sucursal, $array_sucursales)) {
            header("Location: includes/seleccionar_sucursal.php");
            exit();
        }
    } else {
        header("Location: includes/seleccionar_sucursal.php");
        exit();
    }
}

require_once('../../config/database/db_functions.php');

$registros = obtenerZonasFutbol($id_sucursal);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TABLA ZONAS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css"; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css"; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/footer.css"; ?>">
    <link rel="stylesheet" href="css/tablaZonas.css">
    <style>
        .export {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .export button {
            background-color: transparent;
            border: none;
            padding: 0;
            margin: 0;
            margin-top: 1rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .export button i {
            font-size: 35px;
            color: #333;
        }

        .export button:hover i {
            color: #ff0000;
        }
    </style>
</head>

<body>

    <?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php") ?>

    <div class="containerTablaEmpleado">
        <h1 style="text-align:center;">Modulo Zonas de Complejos Deportivos</h1>
        <div class="export">
            <button id="exportarpdf">
                <i class="fa-regular fa-file-pdf"></i>
            </button>
        </div>
        <div id="tabla-container"></div>
        <div id="paginacion-container"></div>
    </div>

    <?php include(RUTA . "includes/footer.php"); ?>

    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "libs/sweetalert2.all.min.js"; ?>"></script>

    <script>
        $(document).on('click', '.eliminar', function() {
            let valor = $(this).attr('valor');
            let sucursal = $(this).attr('sucursal');
            // Mostrar SweetAlert con botones personalizados
            Swal.fire({
                title: '¿Seguro que desea eliminar este registro?',
                text: "No podrás deshacer esta acción",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', // Botón rojo
                cancelButtonColor: '#aaa', // Botón gris
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                // Si el usuario confirma
                if (result.isConfirmed) {
                    // Llamar a la función 'confirmarEliminacion' pasando el id
                    eliminar(valor, sucursal);
                }
            });

        }); // #ELIMINAR ON CLICK


        function eliminar(id, sucursal) {
            window.location.href = "tablaZonas_baja.php?id_zona=" + id + "&id_sucursal=" + sucursal;
        }
    </script>

    <script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/terminoscondiciones.js"; ?>"></script>

    <script>
        $(document).ready(function() {
            let id_sucursal = <?php echo $id_sucursal; ?>;

            function cargarTabla(id_sucursal, filtro = '', pagina = 1) {
                $.ajax({
                    url: 'ajax/obtenerZonas.php',
                    type: 'GET',
                    data: {
                        filtro: filtro,
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
            $('#buscador').on('keyup', function() {
                var filtro = $(this).val();
                cargarTabla(id_sucursal, filtro); //llamar a la funcion con el termino de busqueda
            });

            // Evento para cambiar de página
            $(document).on('click', '.pagina-boton', function() {
                var filtro = $('#buscador').val();
                var page = $(this).data('page');
                cargarTabla(id_sucursal, filtro, page);
            });

        }); // Cierre del DOCUMENT READY
    </script>

    <script>
        // Supongamos que tienes el id_sucursal en una variable
        let id_sucursal = <?php echo $id_sucursal; ?>; // Reemplaza con el valor dinámico que tengas

        // Agregamos un listener al botón
        document.getElementById("exportarpdf").addEventListener("click", function() {
            // Redirige a la URL con el parámetro id_sucursal
            window.location.href = `exportar_pdf.php?id_sucursal=${id_sucursal}`;
        });
    </script>
</body>

</html>

<?php
function obtenerSucursal($id_persona, $id_usuario)
{
    global $conexion;

    $sql_sucursal_empleado = "
            SELECT s.id_sucursal 
            FROM empleado e
            JOIN sucursal s ON e.rela_sucursal = s.id_sucursal
            JOIN persona p ON e.rela_persona = p.id_persona
            JOIN contacto c ON p.id_persona = c.rela_persona
            JOIN usuarios u ON c.id_contacto = u.rela_contacto AND u.id_usuario = ?
            WHERE e.rela_persona = ?";

    $stmt_sucursal_empleado = $conexion->prepare($sql_sucursal_empleado);
    $stmt_sucursal_empleado->bind_param("ii", $id_usuario, $id_persona);

    if ($stmt_sucursal_empleado->execute()) {
        $datos_complejo = $stmt_sucursal_empleado->get_result()->fetch_assoc()['id_sucursal'] ?? false;
        return $datos_complejo;
    }
    return false;
}

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