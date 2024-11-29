$(document).ready(function () {
    alertasFormulario();

    //span errores del servidor
    $('#mensaje-prueba').hide();

    if(mensaje_error) {
        $('#mensaje').html(mensaje_error);
        $('.message-container').show();
    }
});

function alertasFormulario() {
    if(error_documento == "ocupado") {
        Swal.fire("Documento Ocupado","Este documento ya existe, utilice uno nuevo","warning");
    } else if(error_documento == "vacio") {
        Swal.fire("Campos no rellenados documento","Los campos de este formulario son obligatorios","warning");
    }

    if(error_usuario == "ocupado"){
        Swal.fire("Usuario repetido","Las credenciales que eligio ya existen, ingrese unas nuevas","warning");
    } else if (error_usuario == "vacio") {
        Swal.fire("Campos no rellenados usuario","Los campos de este formulario son obligatorios","warning");
    }
}