$(document).on('click', '#aceptar', function() {
    let idReserva = reserva.id_reserva;

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
                    id_reserva: idReserva
                },
                success: function(response) {
                    // Aquí puedes hacer algo con la respuesta
                    if (
                        response == 'todo correcto'
                    ) {
                        Swal.fire(
                            '¡Reserva a sido Aceptada!',
                            'La reserva ha sido Aceptada se le notificara al usuario.',
                            'success'
                        );
                    }

                },
                error: function(xhr, status, error) {
                    // Manejo de error
                    console.log('Error en el Ajax');
                }
            });
        }
    });
});