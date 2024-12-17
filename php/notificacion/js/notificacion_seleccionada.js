$(document).on('click', '.notification-item', function () {
    console.log(notificacion_seleccionada)

    if (notificacion_seleccionada) {
        let element = $(`.notification-item[id-notificacion="${notificacion_seleccionada}"]`);
        if (element.length > 0) {
            let reserva = element.attr('row');
            try {
                reserva = JSON.parse(reserva);
                let predefinedMessage = '';
                showDetails(predefinedMessage, reserva.titulo, reserva.id_notificacion, reserva);
            } catch (error) {
                console.error('Error al parsear JSON:', reserva, error);
            }
        } else {
            console.error('No se encontró el elemento con la notificación seleccionada.');
        }
    } else {
        console.error('notificacion_seleccionada no está definido o es null.');
    }
});
