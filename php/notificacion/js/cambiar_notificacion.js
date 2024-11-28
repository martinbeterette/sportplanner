$(document).on('click', '.notification-item', function() {
    reserva = $(this).attr('row');
    reserva = JSON.parse(reserva);
    predefinedMessage = ``;
    showDetails(predefinedMessage, reserva.titulo, reserva.id_notificacion, reserva);
});