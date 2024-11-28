$(document).ready(function() {
    // Seleccionar el botón de notificaciones
    $('#notificaciones-btn').on('click', function(event) {
        event.preventDefault(); // Evitar que el enlace redirija la página

        // Eliminar la burbuja de notificación
        $('#notificaciones .badge').remove();

        // Realizar la llamada AJAX
        $.ajax({
            url: base_url + 'includes/ajax/leer_notificaciones.php',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Mostrar alerta con SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Notificaciones leídas',
                        text: 'Tus notificaciones se han marcado como leídas.'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo marcar las notificaciones como leídas.'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error en la conexión.'
                });
            }
        });
    });
});
