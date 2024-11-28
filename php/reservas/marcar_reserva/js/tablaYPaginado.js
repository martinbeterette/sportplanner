$(document).ready(function () {
    const today = new Date().toISOString().split('T')[0]; // Fecha de hoy

    // Configuración de Flatpickr
    $("#filtro-fecha").flatpickr({
        dateFormat: "Y-m-d",
        defaultDate: today,
        minDate: today,
        maxDate: new Date(new Date().setDate(new Date().getDate() + 7)), // Máximo 7 días
        onChange: function (selectedDates, dateStr) {
            cargarTabla(id_sucursal, "", dateStr); // Recargar la tabla con la nueva fecha
        },
    });

    // Función para cargar la tabla
    function cargarTabla(id_sucursal, filtro = "", fecha = today, pagina = 1) {
        $.ajax({
            url: "ajax/obtenerReservas.php",
            type: "GET",
            data: { filtro, fecha, pagina, id_sucursal },
            dataType: "json",
            success: function (data) {
                $("#tabla-container").html(data.tabla); // Actualiza la tabla
                actualizarPaginacion(data.total_pages, data.current_page); // Actualiza la paginación
            },
            error: function (xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
            },
        });
    }

    // Función para actualizar la paginación
    function actualizarPaginacion(total_pages, current_page) {
        let paginacionHTML = "";
        for (let i = 1; i <= total_pages; i++) {
            if (i === current_page) {
                paginacionHTML += `<span class="pagina-activa">${i}</span>`;
            } else {
                paginacionHTML += `<button class="pagina-boton" data-page="${i}">${i}</button>`;
            }
        }
        $("#paginacion-container").html(paginacionHTML);
    }

    // Eventos del buscador y botones de paginación
    $("#buscador").on("keyup", function () {
        const filtro = $(this).val();
        const fecha = $("#filtro-fecha").val() || today;
        cargarTabla(id_sucursal, filtro, fecha);
    });

    $(document).on("click", ".pagina-boton", function () {
        const filtro = $("#buscador").val();
        const fecha = $("#filtro-fecha").val() || today;
        const page = $(this).data("page");
        cargarTabla(id_sucursal, filtro, fecha, page);
    });

    // Carga inicial
    cargarTabla(id_sucursal, '', today, pagina_actual);
});
