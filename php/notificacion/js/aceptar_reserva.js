$(document).on('click', '#aceptar', function () {
    let id_reserva      = reserva.id_reserva;
    let id_usuario      = reserva.rela_usuario;
    let fecha_reserva   = reserva.fecha_reserva;
    let id_horario      = reserva.id_horario;

    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Seguro que quieres aceptar la petición de reserva?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, Aceptar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, hacer un AJAX con el id_reserva
            $.ajax({
                url: 'aceptarReserva.php', // Cambia esto por el archivo que recibirá el id_reserva
                method: 'POST',
                data: {
                    id_reserva:     id_reserva,
                    id_usuario:     id_usuario,
                    id_horario:     id_horario,
                    fecha_reserva:  fecha_reserva
                },
                success: function (response) {
                    // Aquí puedes hacer algo con la respuesta
                    if (
                        response == 'todo correcto'
                    ) {
                        Swal.fire(
                            '¡Reserva a sido Aceptada!',
                            'La reserva ha sido Aceptada se le notificara al usuario.',
                            'success'
                        );
                    } else {
                        console.log(response);
                    }

                },
                error: function (xhr, status, error) {
                    // Manejo de error
                    console.log('Error en el Ajax');
                }
            });
        }
    });
});