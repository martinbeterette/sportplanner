<?php
    session_start();
    require_once("../../../config/root_path.php");
    $ruta = RUTA;
    require_once($ruta . "config/database/db_functions/zonas.php");
    require_once($ruta . "config/database/conexion.php");
    $registrosCancha = obtenerZonas();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Cancha</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css" ?>">
    <link rel="stylesheet" href="css/reserva1.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <?php include(RUTA. "includes/header.php") ?>
    <?php include(RUTA. "includes/menu_aside.php") ?>
        <!-- Flechita de breadcrumb -->
    <div class="breadcrumb">
        <a href="<?php echo BASE_URL . "index.php" ?>" class="back-arrow">
            &#8592; Volver
        </a>
    </div>
    <h1>Selecciona una cancha y fecha</h1>
    <div id="tabla-canchas">
        <!-- Aquí se cargará la tabla paginada -->
    </div>

    <form id="form-reserva" method="GET" action="formularioReserva2.php" style="margin-top: 20px; display: none;">
        <h3>Reservar Cancha</h3>
        <input type="hidden" name="cancha" id="cancha_seleccionada">
        <label for="fecha_reserva">Fecha de la reserva:</label>
        <input type="text" id="fecha" name="fecha_reserva" placeholder="fecha" aling="center">
        <button type="submit">Reservar</button>
    </form>
    <script src="<?php echo BASE_URL. "libs/jquery-3.7.1.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/header.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js" ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#fecha", {
            dateFormat: "Y-m-d",  // Formato de fecha almacenado
            minDate: "today",     // Hoy como la fecha mínima
            maxDate: new Date().fp_incr(7),  // 7 días hacia adelante desde hoy
            defaultDate: "today", // Preselecciona la fecha de hoy
            altInput: true,
            altFormat: "F j, Y",  // Formato alternativo que se muestra
            allowInput: false,     // Evita que el usuario escriba manualmente
        });
    </script>
    <script>
        $(document).ready(function () {
            // Función para cargar la tabla con paginación
            function cargarTabla(pagina = 1) {
                $.ajax({
                    url: 'includes/paginacion_canchas.php', // Archivo PHP que devuelve los datos
                    type: 'GET',
                    data: { pagina: pagina },
                    dataType: 'json',
                    success: function (respuesta) {
                        let tablaHtml = `
                            <table border="1" style="width: 100%; text-align: left;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Descripción</th>
                                        <th>Sucursal</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                            // Iterar sobre las canchas para generar filas
                            respuesta.data.forEach(c => {
                                tablaHtml += `
                                    <tr>
                                        <td>${c.id_zona}</td>
                                        <td>${c.descripcion_zona}</td>
                                        <td>${c.descripcion_sucursal}</td>
                                        <td>
                                            <button class="seleccionar-cancha" data-id="${c.id_zona}">Seleccionar</button>
                                        </td>
                                    </tr>`;
                            });

                            tablaHtml += `</tbody></table>`;

                            // Paginación
                            let paginacionHtml = '<div style="margin-top: 10px;">';
                            for (let i = 1; i <= respuesta.totalPages; i++) {
                                paginacionHtml += `<button class="pagina" data-pagina="${i}">${i}</button>`;
                            }
                            paginacionHtml += '</div>';

                            $('#tabla-canchas').html(tablaHtml + paginacionHtml);
                        },
                        error: function (error) {
                            console.error('Error al cargar las canchas:', error.responseText);
                        }
                    });
                }

                // Cargar la tabla al iniciar
                cargarTabla();

                // Manejar cambio de página
                $(document).on('click', '.pagina', function () {
                    let pagina = $(this).data('pagina');
                    cargarTabla(pagina);
                });

                // Manejar selección de cancha
                $(document).on('click', '.seleccionar-cancha', function () {
                    let idCancha = $(this).data('id');
                    $('#cancha_seleccionada').val(idCancha);
                    $('#form-reserva').slideDown(); // Mostrar el formulario
                    $('html, body').animate({
                        scrollTop: $("#form-reserva").offset().top
                    }, 500);
                });

                // Manejar el envío del formulario
                
                });

</script>
</body>
</html>