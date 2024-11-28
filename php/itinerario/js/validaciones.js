$(document).ready(function() {
    

    // Cargar las profesiones cuando se seleccione una persona
        $('input[type="checkbox"]').prop('checked', false);
        if (id_sucursal) {

            $.ajax({
                url: 'ajax/cargar_itinerario.php',
                method: 'GET',
                data: { id_sucursal: id_sucursal },
                dataType: 'json',
                success: function(data) {
                    var divDias = $('#dias');                   

                    // Marcar los checkboxes que corresponden a las profesiones de la persona seleccionada
                    $.each(data, function(index, dia) {
                        $('input[type="checkbox"][value="' + dia.rela_dia + '"]').prop('checked', true);
                    });
                },
                error: function() {
                    console.log('Error al cargar las profesiones.');
                }
            });

        } 


});
