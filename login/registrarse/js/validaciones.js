$(document).ready(function() {
    // Inicializamos los pan para ocultarlos
    var spanerror = $('#mensaje-error');
    spanerror.hide();

    // Función para limpiar los estilos de error
    function limpiarError(input) {
        input.css('border', ''); // Restablece el borde
        spanerror.hide(); // Oculta el mensaje de error
    }

    // Función para mostrar el error
    function mostrarError(input, mensaje) {
        input.css('border', '2px solid #FF4500'); // Borde rojo
        spanerror.css({
            'display': 'inline-block',
            'background-color': '#FF4500',
            'margin': '10px',
            'color': 'white',
            'padding': '10px'
        });
        spanerror.html(mensaje);
    }

    $('form').on('submit', function(event) {
        // Obtenemos los valores de los inputs
        var nombreInput = $('#nombre');
        var apellidoInput = $('#apellido');
        var dniInput = $('#dni');
        var emailInput = $('#email');
        var usuarioInput = $('#username');
        var contrasenaInput = $('#password');
        var contrasenaInput2 = $('#password2');

        var nombre = nombreInput.val();
        var apellido = apellidoInput.val();
        var dni = dniInput.val();
        var email = emailInput.val();
        var usuario = usuarioInput.val();
        var contrasena = contrasenaInput.val();
        var contrasena2 = contrasenaInput2.val();

        var regexusuario = /^(?=.*[a-zA-Z])[a-zA-Z\d]{5,}$/;
        var regexcontrasena = /^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
        var regexEmail = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

        // Validación del nombre
        if (nombre == '') {
            event.preventDefault();
            mostrarError(nombreInput, 'Ingrese un Nombre');
        } else {
            limpiarError(nombreInput);
        }

        // Validación del apellido
        if (apellido == '') {
            event.preventDefault();
            mostrarError(apellidoInput, 'Ingrese un Apellido');
        } else {
            limpiarError(apellidoInput);
        }

        // Validación del DNI
        if (dni == '') {
            event.preventDefault();
            mostrarError(dniInput, 'Ingrese un DNI');
        } else {
            limpiarError(dniInput);
        }

        // Validación del email
        if (email == '') {
            event.preventDefault();
            mostrarError(emailInput, 'Ingrese un Correo');
        } else if (!regexEmail.test(email)) {
            event.preventDefault();
            mostrarError(emailInput, 'Correo no Valido');
        } else {
            limpiarError(emailInput);
        }

        // Validación del usuario
        if (!regexusuario.test(usuario)) {
            event.preventDefault();
            mostrarError(usuarioInput, 'Usuario Invalido');
        } else {
            limpiarError(usuarioInput);
        }

        // Validación de la contraseña
        if (!regexcontrasena.test(contrasena)) {
            event.preventDefault();
            mostrarError(contrasenaInput, 'Contraseña no Valida');
        } else {
            limpiarError(contrasenaInput);
        }

        // Validación de las contraseñas coincidentes
        if (contrasena !== contrasena2) {
            event.preventDefault();
            mostrarError(contrasenaInput, 'Las contraseñas no coinciden');
            mostrarError(contrasenaInput2, 'Las contraseñas no coinciden');
        } else if (contrasena === "" || contrasena2 === "") {
            event.preventDefault();
            mostrarError(contrasenaInput, 'Rellene las contraseñas');
            mostrarError(contrasenaInput2, 'Rellene las contraseñas');
        } else {
            limpiarError(contrasenaInput);
            limpiarError(contrasenaInput2);
        }
    });
});
