$(document).ready(function() {

    function cargarTabla(id_sucursal,filtro = '', pagina = 1) {
        $.ajax({
            url: 'ajax/obtenerEmpleados.php',
            type: 'GET',
            data: { filtro: filtro, pagina: pagina , id_sucursal: id_sucursal},
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
        cargarTabla(id_sucursal ,filtro); //llamar a la funcion con el termino de busqueda
    });

    // Evento para cambiar de página
    $(document).on('click', '.pagina-boton', function() {
        var filtro = $('#buscador').val();
        var page = $(this).data('page');
        cargarTabla(id_sucursal,filtro, page);
    });

}); // Cierre del DOCUMENT READY