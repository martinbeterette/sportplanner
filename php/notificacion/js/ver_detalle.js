$(document).on('click', '.ver_detalle', function() {
    let idReserva = reserva.id_reserva;
    let detalle = `
        <div class="detalleModal">
            <div class="datos"><strong>Nombre:</strong> <p>${reserva.nombre}</p></div>
            <div class="datos"><strong>Apellido:</strong> <p>${reserva.apellido}</p></div>
            <div class="datos"><strong>Horario:</strong> <p>${reserva.horario_inicio} - ${reserva.horario_fin}</p></div>
            <div class="datos"><strong>Fecha de Reserva:</strong> <p>${reserva.fecha_reserva}</p></div>
            <div class="datos"><strong>Fecha de Alta:</strong> <p>${reserva.fecha_alta}</p></div>  
        </div>`;

    Swal.fire({
        title: 'Detalle Reserva',
        html: detalle,
        imageUrl: '../../assets/icons/CarbonReport.svg', // URL del icono
        imageWidth: 100, // Ancho del icono
        imageHeight: 100, // Alto del icono
    });
});