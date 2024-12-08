$(document).ready(function () {
    $('#altaEmpleadoForm').on('submit', function (e) {
        e.preventDefault(); // Detener el envío del formulario

        const paso = $(e.originalEvent.submitter).attr('name'); // Qué botón fue presionado
        const errores = {};

        // Limpiar mensajes de error previos
        $('span').text('');

        // Valores del formulario
        const correo = $('#correo').val().trim();
        const username = $('#username').val().trim();
        const password = $('#password').val().trim();
        const confirmPassword = $('#confirm-password').val().trim();
        const nombre = $('#nombre').val() || '';
        const apellido = $('#apellido').val() || '';
        const documento = $('#documento').val() || '';
        const tipoDocumento = $('#tipo_documento').val() || '';
        const sexo = $('#sexo').val() || '';
        const domicilio = $('#domicilio').val() || '';

        // Validar campos paso 1
        if (!validarCorreo(correo)) {
            errores.correo = "Correo inválido.";
        }
        if (!validarUsername(username)) {
            errores.username = "Usuario inválido.";
        }
        if (!validarPassword(password)) {
            errores.password = "Contraseña inválida.";
        }
        if (password !== confirmPassword) {
            errores['confirm-password'] = "Las contraseñas no coinciden.";
        }

        // Validar campos paso 2 si el botón es paso2
        if (paso === 'paso2') {
            if (!validarNombre(nombre)) {
                errores.nombre = "Nombre inválido.";
            }
            if (!validarApellido(apellido)) {
                errores.apellido = "Apellido inválido.";
            }
            if (!validarDocumento(documento, tipoDocumento)) {
                errores.documento = "Documento inválido.";
            }
            if (!validarSexo(sexo)) {
                errores.sexo = "Sexo inválido.";
            }
            if (!validarDomicilio(domicilio)) {
                errores.domicilio = "Domicilio inválido.";
            }
        }

        // Mostrar errores o enviar formulario
        if (!$.isEmptyObject(errores)) {
            for (const campo in errores) {
                $(`#${campo}Status`).text(errores[campo]).css('color', 'red');
            }
        } else {
            this.submit(); // Enviar formulario si no hay errores
        }
    });

    // Funciones de validación
    function validarCorreo(correo) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo);
    }

    function validarUsername(username) {
        return /^[\w.-]+$/.test(username);
    }

    function validarPassword(password) {
        return /^[\w.*-]+$/.test(password);
    }

    function validarNombre(nombre) {
        return /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(nombre);
    }

    function validarApellido(apellido) {
        return /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(apellido);
    }

    function validarDocumento(documento, tipoDocumento) {
        if (tipoDocumento === '1') { // DNI
            const documentoLimpio = documento.replace(/\./g, '');
            return /^\d+$/.test(documentoLimpio);
        } else { // Otros tipos
            return /^[\w]+$/.test(documento);
        }
    }

    function validarSexo(sexo) {
        return /^\d+$/.test(sexo);
    }

    function validarDomicilio(domicilio) {
        return /^[\w\s\.\-,°]+$/.test(domicilio);
    }
});
