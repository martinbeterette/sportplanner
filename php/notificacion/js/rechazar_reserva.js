$(document).on('click', '#rechazar', function () {
    let id_reserva = reserva.id_reserva;
    let id_usuario = reserva.rela_usuario;

    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Seguro que quieres rechazar la petición de reserva?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, Rechazar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, hacer un AJAX con el id_reserva
            $.ajax({
                url: 'rechazarReserva.php', // Cambia esto por el archivo que recibirá el id_reserva
                method: 'POST',
                data: {
                    id_reserva: id_reserva,
                    id_usuario: id_usuario
                },
                success: function (response) {
                    // Aquí puedes hacer algo con la respuesta
                    if (
                        response == 'todo correcto'
                    ) {
                        Swal.fire(
                            '¡Reserva a sido Rechazada!',
                            'La reserva ha sido Rechazada se le notificara al usuario.',
                            'success'
                        );
                    } else {
                        console.log(response)
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