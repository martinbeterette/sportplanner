$(document).ready(function() {
    // Cargar las personas en el select
    $.ajax({
        url: 'perfiles.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            var selectPerfil = $('#perfil');

            selectPerfil.append($('<option>', {
                value: '',
                text: 'Seleccione un perfil'
            }));

            $.each(data, function(index, perfil) {
                selectPerfil.append($('<option>', {
                    value: perfil.id_perfil,
                    text: perfil.descripcion_perfil
                }));
            });
        },
        error: function() {
            console.log('Error al cargar la lista de personas.');
        }
    });

    // Cargar las profesiones cuando se seleccione una persona
    $('#perfil').change(function() {
        var idPerfil = $(this).val();
        $('input[type="checkbox"]').prop('checked', false);
        if (idPerfil) {

            $.ajax({
                url: 'modulos_por_perfil.php',
                method: 'GET',
                data: { id_perfil: idPerfil },
                dataType: 'json',
                success: function(data) {
                    var divModulos = $('#modulos');                   

                    // Marcar los checkboxes que corresponden a las profesiones de la persona seleccionada
                    $.each(data, function(index, modulo) {
                        $('input[type="checkbox"][value="' + modulo.rela_modulo + '"]').prop('checked', true);
                    });
                },
                error: function() {
                    console.log('Error al cargar las profesiones.');
                }
            });

        } 
    });


});
