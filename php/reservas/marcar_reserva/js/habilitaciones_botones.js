$(document).ready(function () {
    // Iterar sobre cada fila de la tabla
    $("tr").each(function () {
        // Obtener el ID de la reserva desde el atributo data-id del botón "Marcar Llegada"
        const reservaId = $(this).find(".btn-llegada").data("id");

        // Obtener el texto de la columna "entrada" (ajusta el índice si es necesario)
        const entrada = $(this).find("td:nth-child(8)").text().trim(); // Columna "entrada"
        
        // Seleccionar el botón "Marcar Salida" correspondiente
        const btnSalida = $(this).find(`.btn-salida[data-id="${reservaId}"]`);

        // Si hay un valor en la columna "entrada", habilitar el botón "Marcar Salida"
        if (entrada) {
            btnSalida.prop("disabled", false);
        } else {
            btnSalida.prop("disabled", true);
        }
    });
});