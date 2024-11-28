
$(document).ready(function () {

    function mostrarInfoReserva(idReserva) {
        const reserva = $(this).attr('registros');

        Swal.fire({
            title: `Información de Reserva #${idReserva}`,
            html: `
                <ul style="text-align: left; list-style-type: none; padding: 0;">
                    <li><strong>Hora de inicio:</strong> ${reserva.hora_inicio}</li>
                    <li><strong>Hora de Fin:</strong> ${reserva.hora_fin}</li>
                    <li><strong>Fecha de reserva:</strong> ${reserva.fecha}</li>
                    <li><strong>Nombre de Usuario:</strong> ${reserva.usuario}</li>
                    <li><strong>Titular:</strong> ${reserva.titular}</li>
                    <li><strong>Precio:</strong> ${reserva.precio}</li>
                    <li><strong>Monto Pagado:</strong> ${reserva.monto_pagado}</li>
                </ul>
            `,
            showCancelButton: true,
            showConfirmButton: false,
            cancelButtonText: 'Cerrar',
            footer: `
                <button onclick="marcarLlegada(${idReserva})" class="swal2-confirm swal2-styled">Marcar Llegada</button>
                <button onclick="marcarSalida(${idReserva})" class="swal2-cancel swal2-styled" style="background-color: #ffc107;">Marcar Salida</button>
                <button onclick="marcarInasistencia(${idReserva})" class="swal2-deny swal2-styled" style="background-color: #dc3545;">Inasistencia</button>
            `
        });
    }

    // Funciones de ejemplo para los botones
    function marcarLlegada(idReserva) {
        Swal.fire({
            icon: 'success',
            title: `Llegada marcada para reserva #${idReserva}`,
            timer: 1500,
            showConfirmButton: false
        });
        // Aquí puedes hacer una llamada AJAX para actualizar la base de datos
    }

    function marcarSalida(idReserva) {
        Swal.fire({
            icon: 'info',
            title: `Salida marcada para reserva #${idReserva}`,
            timer: 1500,
            showConfirmButton: false
        });
        // Aquí puedes hacer una llamada AJAX para actualizar la base de datos
    }

    function marcarInasistencia(idReserva) {
        Swal.fire({
            icon: 'warning',
            title: `Inasistencia marcada para reserva #${idReserva}`,
            timer: 1500,
            showConfirmButton: false
        });
    }

}); // document ready