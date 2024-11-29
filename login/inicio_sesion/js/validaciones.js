var spanInput = $('span:eq(0)');
var spancontrasena = $('span:eq(1)');

spanInput.hide();
spancontrasena.hide();

$('form').on('submit', function(event) {
    // Obtener las variables para operar la validación
    var usernameInput = $('#username');
    var contrasenaInput = $('#password');

    // Ocultar los span antes de la validación
    spanInput.hide();
    spancontrasena.hide();

    // Obtener los valores de los inputs
    var username = usernameInput.val();
    var contrasena = contrasenaInput.val();

    // Expresiones regulares para validar
    var regexusername = /^[a-zA-Z0-9._-]{4,}$/;
    var regexcontrasena = /^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;

    // Validación de username
    if (!regexusername.test(username)) {
        event.preventDefault(); // Evitar el envío del formulario
        usernameInput.css('border', '2px solid #FF4500');
        spanInput.css({
            'display': 'inline-block',
            'background-color': '#FF4500',
            'margin': '10px',
            'color': 'white',
            'padding': '10px'
        });
        spanInput.html('almenos 4 caracteres, solo guiones y guiones bajos');
    } else {
        usernameInput.css('border', '2px solid green');
        spanInput.css({
            'display':'none',
        });
    }

    // Validación de la contraseña
    if (!regexcontrasena.test(contrasena)) {
        event.preventDefault(); // Evitar el envío del formulario
        contrasenaInput.css('border', '2px solid #FF4500');
        spancontrasena.css({
            'display': 'inline-block',
            'background-color': '#FF4500',
            'margin': '10px',
            'color': 'white',
            'padding': '10px'
        });
        spancontrasena.html('Contraseña inválida. Debe tener al menos 8 caracteres y 1 número.');
    } else {
        contrasenaInput.css('border', '2px solid green');
        spancontrasena.css({
            'display':'none',
        });
    }

});
