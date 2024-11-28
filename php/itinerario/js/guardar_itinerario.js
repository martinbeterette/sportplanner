$(document).ready(function () {
    // Manejo del envío del formulario
    $('#itinerario-form').on('submit', function (e) {
        e.preventDefault();
        const data = $(this).serialize();

        $.ajax({
            url: 'ajax/guardar_itinerario.php', // El archivo actual maneja el guardado
            type: 'POST',
            data: data,
            success: function (response) {
                $('#resultado').text(response).css('color', 'green');
            },
            error: function () {
                $('#resultado').text('Error al guardar el itinerario.').css('color', 'red');
            }
        });
    });
});