$(document).ready(function () {

    $(document).on('click', '.eliminar', function() {
        let id_reserva = $(this).attr('valor');
        // Mostrar SweetAlert con botones personalizados
        

        Swal.fire({
            title: '¿Seguro que desea Cancelar esta reserva?',
            text: 'Adjuntar motivo de cancelación',
            input: 'text', // Campo de texto para el motivo de cancelación
            inputPlaceholder: 'Motivo de cancelación',
            inputAttributes: {
                'aria-label': 'Motivo de cancelación' // Mejor accesibilidad
            },
            showCancelButton: true,
            confirmButtonText: 'Confirmar Cancelación',
            cancelButtonText: 'Cerrar',
            width: '600px',
            padding: '2rem',
            background: '#fff',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#47a386',
            showClass: {
                popup: 'animate__animated animate__fadeIn'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOut'
            },
            preConfirm: (motivo) => {
                // Verificar que el motivo no esté vacío
                if (!motivo) {
                    Swal.showValidationMessage('El motivo de cancelación es obligatorio');
                } else {
                    return motivo; // Retorna el motivo si está lleno
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let motivo = result.value; // Obtiene el motivo ingresado
                eliminar(id_reserva, motivo); // Llama a la función con el motivo
            }
        });
    }); // #ELIMINAR ON CLICK

    function eliminar(id_reserva, motivo) {
        window.location.href = "includes/eliminar.php?id_reserva=" + encodeURIComponent(id_reserva) + "&motivo=" + encodeURIComponent(motivo);
    }

}); //document ready