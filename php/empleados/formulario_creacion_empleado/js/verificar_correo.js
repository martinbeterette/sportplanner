$(document).ready(function(){

    let correo = $('#correo').val();
    verificarCorreoExistente(correo);

    let debounceTimer;
    $('#correo').on('blur', function(){

        clearTimeout(debounceTimer);
        var correo = $(this).val();

        
        debounceTimer = setTimeout(function(){
            verificarCorreoExistente(correo);
        }, 300);
    });

    function verificarCorreoExistente(correo) {
        if (correo) {
            $.ajax({
                url: 'ajax/verificar_correo_existente.php',  // archivo PHP para verificar el correo
                type: 'POST',
                data: {correo: correo},
                success: function(response){
                    if(response === "existe"){
                        $('#correoStatus').text("Este correo ya tiene una cuenta asignada.");
                        $('#correoStatus').css({'color': 'green'});
                        $('#paso2').hide(); // Ocultamos el paso 2

                        //intercambiamos el btn siguiente por finalizar(ya tenemos los datos)
                        $('#btn-siguiente').attr('type', 'submit').text('Finalizar');
                    } else {
                        $('#correoStatus').text("Correo nuevo, continuar con el registro.");
                        $('#correoStatus').css({'color': '#e57373'});
                        $('#btn-siguiente').attr('type', 'button').text('siguiente'); 
                    }
                },
                error: function () {
                    console.log("error en la peticion AJAX");
                }
            });
        } else {
            $('#correoStatus').text("");
            $('#btn-siguiente').attr('type', 'button').text('siguiente');
        }
    }
});

