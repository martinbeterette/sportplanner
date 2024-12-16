$(document).on('click', '.ver_detalle', function () {
    let idReserva = reserva.id_reserva;
    let detalle = `
        <div class="detalleModal">
            <div class="datos"><strong>Nombre:</strong> <p>${reserva.nombre}</p></div>
            <div class="datos"><strong>Apellido:</strong> <p>${reserva.apellido}</p></div>
            <div class="datos"><strong>dni:</strong> <p>${reserva.descripcion_documento}</p></div>
            <div class="datos"><strong>Horario:</strong> <p>${reserva.horario_inicio} - ${reserva.horario_fin}</p></div>
            <div class="datos"><strong>Fecha de Reserva:</strong> <p>${reserva.fecha_reserva}</p></div>
            <div class="datos"><strong>Fecha de Alta:</strong> <p>${reserva.fecha_alta}</p></div>
            <div class="datos"><strong>zona:</strong> <p>${reserva.descripcion_zona}</p></div>
            <div class="datos"><strong>estado reserva:</strong> <p>${reserva.rela_estado_reserva}</p></div>
            <div class="datos"><strong>id reserva:</strong> <p>${reserva.id_reserva}</p></div>
            <div class="datos"><strong>id horario:</strong> <p>${reserva.id_horario}</p></div>
        </div>`;

    Swal.fire({
        title: 'Detalle Reserva',
        html: detalle,
        imageUrl: '../../assets/icons/CarbonReport.svg', // URL del icono
        imageWidth: 100, // Ancho del icono
        imageHeight: 100, // Alto del icono
    });
});

$(document).on('click', '.ver_reserva', function () {
    let id_reserva = reserva.id_reserva;
    $.ajax({
        url: 'ajax/verDetalle.php',
        method: 'GET',
        data: {
            id_reserva: id_reserva
        },
        dataType: 'json',
        success: function (respuesta) {
            if (respuesta.estado == true) {
                let html = `
                <p><strong>ID Reserva:</strong> ${respuesta.mensaje.id_reserva}</p>
                <p><strong>Fecha:</strong> ${respuesta.mensaje.fecha_reserva}</p>
                <p><strong>Zona:</strong> ${respuesta.mensaje.descripcion_zona}</p>
                <p><strong>Sucursal:</strong> ${respuesta.mensaje.descripcion_sucursal}</p>
                `;
                Swal.fire({
                    icon: "success",
                    html: html
                });
            } else {
                console.log(respuesta.mensaje);
            }
        }
    });





















    // let detalle = `
    //     <div class="detalleModal">
    //         <div class="datos"><strong>Nombre:</strong> <p>${reserva.nombre}</p></div>
    //         <div class="datos"><strong>Apellido:</strong> <p>${reserva.apellido}</p></div>
    //         <div class="datos"><strong>Horario:</strong> <p>${reserva.horario_inicio} - ${reserva.horario_fin}</p></div>
    //         <div class="datos"><strong>Fecha de Reserva:</strong> <p>${reserva.fecha_reserva}</p></div>
    //         <div class="datos"><strong>Fecha de Alta:</strong> <p>${reserva.fecha_alta}</p></div>  
    //     </div>`;

    // Swal.fire({
    //     title: 'Detalle Reserva',
    //     html: detalle,
    //     imageUrl: '../../assets/icons/CarbonReport.svg', // URL del icono
    //     imageWidth: 100, // Ancho del icono
    //     imageHeight: 100, // Alto del icono
    // });
});