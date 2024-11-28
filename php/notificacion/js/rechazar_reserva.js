$(document).on('click', '#rechazar', function() {
    let idReserva = reserva.id_reserva;

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
                    id_reserva: idReserva
                },
                success: function(response) {
                    // Aquí puedes hacer algo con la respuesta
                    if (
                        response == 'todo correcto'
                    ) {
                        Swal.fire(
                            '¡Reserva a sido Rechazada!',
                            'La reserva ha sido Rechazada se le notificara al usuario.',
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