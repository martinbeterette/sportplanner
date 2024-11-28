document.getElementById('telefono').addEventListener('input', function () {
    const telefonoInput = this.value;
    const regexTelefono = /^\d{10}$/;
    const errorMensaje = document.getElementById('error_contacto');

    if (!regexTelefono.test(telefonoInput)) {
        errorMensaje.style.display = 'block';
    } else {
        errorMensaje.style.display = 'none';
    }
});
