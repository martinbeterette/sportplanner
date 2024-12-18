<?php
session_start();
require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
require_once(RUTA . "php/functions/consulta_reutilizable_mysql.php");

if (isset($_GET['id_complejo'])) {
    $id_complejo = $_GET['id_complejo'];
} else {
    echo "ha ocurrido un error :( falta get de complejo" . "<br>";
    echo "<a href='" . BASE_URL . "index.php" . "'>Volver</a>";
    die;
}



// Define las variables reutilizables
$titulo_pagina = "Socios";
$modulo = "Lista de socios de un complejo";

// definimos los campos del encabezado
$thead = ['ID', 'Nombre', 'Apellido', 'Documento', 'Membresia', 'Eliminar'];

// Define los campos a seleccionar
$campos = ['id_socio as id', 'id_persona', 'nombre', 'apellido', 'descripcion_documento', 'descripcion_membresia'];
$tabla = 'socio'; // La tabla principal

$join = 'JOIN persona
            ON socio.rela_persona = persona.id_persona
            JOIN documento
            ON persona.rela_documento = documento.id_documento
            JOIN membresia
            ON socio.rela_membresia = membresia.id_membresia
';

$condicion = "rela_complejo = $id_complejo AND socio.estado IN(1)";

$orden = '';

// $registros = obtenerRegistros($tabla, $campos, $join, $condicion);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo_pagina; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/tabla_socio.css">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css"; ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css"; ?>">

</head>

<body>

    <?php include(RUTA . "includes/header.php"); ?>

    <?php include(RUTA . "includes/menu_aside.php"); ?>

    <div class="containerEmpleado">
        <h1>Socios</h1>
        <input type="text" id="buscador" placeholder="Buscar...">
        <div id="tabla-container"></div>
        <div id="paginacion-container"></div>
    </div>

    <script src="../../libs/jquery-3.7.1.min.js"></script>
    <script src="../../libs/sweetalert2.all.min.js"></script>

    <script>
        $(document).on('click', '.eliminar', function() {
            let valor = $(this).attr('valor');
            let complejo = $(this).attr('complejo');
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
                    eliminar(valor, complejo);
                }
            });

        }); // #ELIMINAR ON CLICK


        function eliminar(id, complejo) {
            window.location.href = "eliminar.php?id=" + id + "&id_complejo=" + complejo;
        }
    </script>

    <script src="<?php echo BASE_URL . "js/header.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js"; ?>"></script>

    <script>
        $(document).ready(function() {
            let id_complejo = <?php echo $id_complejo; ?>;

            function cargarTabla(id_complejo, filtro = '', pagina = 1) {
                $.ajax({
                    url: 'ajax/obtenerSocios.php',
                    type: 'GET',
                    data: {
                        filtro: filtro,
                        pagina: pagina,
                        id_complejo: id_complejo
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
            cargarTabla(id_complejo);

            // Evento de búsqueda
            $('#buscador').on('keyup', function() {
                var filtro = $(this).val();
                cargarTabla(id_complejo, filtro); //llamar a la funcion con el termino de busqueda
            });

            // Evento para cambiar de página
            $(document).on('click', '.pagina-boton', function() {
                var filtro = $('#buscador').val();
                var page = $(this).data('page');
                cargarTabla(id_complejo, filtro, page);
            });

        }); // Cierre del DOCUMENT READY
    </script>
</body>

</html>