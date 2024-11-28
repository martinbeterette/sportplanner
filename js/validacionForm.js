// Función que valida los campos en tiempo real
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".containerEmpleado form");

    // Expresiones regulares para la validación de los campos
    const regex = {
        nombre: /^[a-zA-Z\s]{2,20}$/, // Solo letras y espacios, entre 2 y 20 caracteres
        apellido: /^[a-zA-Z\s]{2,15}$/, // Igual que el nombre
        documento: /^\d{7,8}$/, // Solo números, entre 7 y 8 dígitos
        fecha_nacimiento: /^\d{4}-\d{2}-\d{2}$/ // Validación básica para fechas en formato AAAA-MM-DD
    };

    // Función para mostrar un mensaje de error
    function mostrarError(input, mensaje) {
        let error = input.nextElementSibling;
        if (!error || !error.classList.contains('error')) {
            error = document.createElement('div');
            error.classList.add('error');
            input.insertAdjacentElement('afterend', error);
        }
        error.textContent = mensaje;
        input.classList.add('input-error');
    }

    // Función para limpiar el mensaje de error
    function limpiarError(input) {
        let error = input.nextElementSibling;
        if (error && error.classList.contains('error')) {
            error.textContent = '';
            input.classList.remove('input-error');
        }
    }

    // Función de validación individual
    function validarInput(input, regex, mensaje) {
        input.addEventListener("input", function () {
            if (!regex.test(input.value)) {
                mostrarError(input, mensaje);
            } else {
                limpiarError(input);
            }
        });
    }

    // Validar cada campo con la expresión regular correspondiente
    validarInput(document.getElementById("nombre"), regex.nombre, "El nombre solo puede contener letras y espacios, y debe tener entre 2 y 20 caracteres.");
    validarInput(document.getElementById("apellido"), regex.apellido, "El apellido solo puede contener letras y espacios, y debe tener entre 2 y 20 caracteres.");
    validarInput(document.getElementById("documento"), regex.documento, "El documento debe contener entre 7 y 8 dígitos numéricos.");
    validarInput(document.getElementById("fecha_nacimiento"), regex.fecha_nacimiento, "La fecha debe tener el formato AAAA-MM-DD.");

    // Validar el envío del formulario
    form.addEventListener("submit", function (event) {
        let inputs = form.querySelectorAll("input, select");
        let formValido = true;

        inputs.forEach(function (input) {
            if (input.value === "") {
                mostrarError(input, "Este campo es obligatorio.");
                formValido = false;
            } else {
                limpiarError(input);
            }
        });

        if (!formValido) {
            event.preventDefault(); // Prevenir el envío si el formulario no es válido
        }
    });
});
