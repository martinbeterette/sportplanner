let predefinedMessage;
let btnhtml;
$(document).on('click', '.notification-item', function () {
    reserva = $(this).attr('row');
    reserva = JSON.parse(reserva);
    let predefinedMessage = '';
    let btnhtml = '';
    console.log(reserva.categoria);
    switch (reserva.categoria) {
        case 'reserva':
            predefinedMessage = `La persona Nombre: ${reserva.nombre} Apellido: ${reserva.apellido} DNI: ${reserva.descripcion_documento} desea reservar la cancha ${reserva.id_zona} de ${reserva.horario_inicio} - ${reserva.horario_fin}`;
            btnhtml = `<div class="action-buttons">
                <button class="accept-btn" id="aceptar">Aceptar</button>
                <button class="reject-btn" id="rechazar">Rechazar</button>
                <button class="view-btn ver_detalle" id="ver_detalle">Ver Detalle</button>
            </div>`;
            break;
        case 'cancelacion':
            predefinedMessage = 'La reserva no fue concretada por motivos de mantenimiento.';
            btnhtml = `
                 <div class="action-buttons">
                     <button class="accept-btn ver_reserva">Ver Reserva</button>
                 </div>`;
            break;
        case 'confirmacion':
            predefinedMessage = 'La reserva fue Aceptada, lo podra ver detalladamente en Mis Reservas.';
            btnhtml = `
                 <div class="action-buttons">
                     <button class="accept-btn ver_reserva">Ver Reserva</button>
                 </div>`;
            break;
        default:
            predefinedMessage = 'no hay mensaje. aguante messi!';
            break;
    }

    // Marcar la notificación como "leída" mediante AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'marcar_leido.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send('id=' + reserva.id_notificacion);

    $('#details').html(`${predefinedMessage}${btnhtml} `);
});