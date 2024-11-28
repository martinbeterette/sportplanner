$(document).ready(function () {
    if (notificacion_seleccionada !== null) {
        reserva = $(`.notification-item[id-notificacion="${notificacion_seleccionada}"]`).attr('row');
        reserva = JSON.parse(reserva);
        predefinedMessage = '';
        showDetails(predefinedMessage,reserva.titulo,reserva.id_notificacion,reserva);
    }
});