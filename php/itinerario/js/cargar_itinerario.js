$(document).ready(function () {
    $('#sucursal_id').on('change', function () {
        let sucursalId = $(this).val();

        // Limpiar los valores previos
        $('#dias_habiles input[type="checkbox"]').prop('checked', false);
        $('#hora_apertura').val('');
        $('#hora_cierre').val('');

        if (sucursalId) {
            // Hacer la llamada AJAX para obtener el itinerario
            $.ajax({
                url: 'ajax/cargar_itinerario.php',
                type: 'GET',
                data: { sucursal_id: sucursalId },
                dataType: 'json',
                success: function (response) {
                    console.log(response); // Para verificar el formato y contenido
                    if (response.error) {
                        $('#resultado').text(response.error).css('color', 'red');
                    } else {
                        // Marcar los días y establecer los horarios
                        response.forEach(item => {
                            console.log(item.dia_semana); // Verifica qué valores llegan
                            $(`#dias_habiles input[value="${item.dia_semana}"]`).prop('checked', true);
                        });

                        if (response.length > 0) {
                            $('#hora_apertura').val(response[0].hora_apertura);
                            $('#hora_cierre').val(response[0].hora_cierre);
                        }
                    }
                },
                error: function () {
                    $('#resultado').text('Error al cargar el itinerario.').css('color', 'red');
                }
            });
        }
    });
});
