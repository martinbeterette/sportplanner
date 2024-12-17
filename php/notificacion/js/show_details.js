// function showDetails(message, title, idNotificacion, reserva) {
//     const detailsContainer = document.getElementById('details');
//     let buttonsHTML = '';
//     let predefinedMessage = '';

//     switch (title) {
//         case 'Reserva':
//             predefinedMessage = `La persona Nombre: ${reserva.nombre} Apellido: ${reserva.apellido} DNI: ${reserva.descripcion_documento} desea reservar la cancha ${reserva.id_zona} de ${reserva.horario_inicio} - ${reserva.horario_fin}`;
//             buttonsHTML = `
//         <div class="action-buttons">
//             <button class="accept-btn" id="aceptar">Aceptar</button>
//             <button class="reject-btn" id="rechazar">Rechazar</button>
//             <button class="view-btn ver_detalle" id="ver_detalle">Ver Detalle</button>
//         </div>`;
//             break;
//         case 'Cancelacion':
//             predefinedMessage = 'La reserva no fue concretada por motivos de mantenimiento.';
//             break;
//         case 'Confirmacion':
//             predefinedMessage = 'La reserva fue Aceptada, lo podra ver detalladamente en Mis Reservas.';
//             buttonsHTML = `
//         <div class="action-buttons">
//             <button class="accept-btn ver_detalle">Ver Detalle</button>
//         </div>`;
//             break;
//         default:
//             predefinedMessage = message;
//             break;
//     }

//     detailsContainer.innerHTML = `<p>${predefinedMessage}</p>${buttonsHTML}`;

//     // Marcar la notificación como "leída" mediante AJAX
//     var xhr = new XMLHttpRequest();
//     xhr.open('POST', 'marcar_leido.php', true);
//     xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
//     xhr.send('id=' + idNotificacion);
// }