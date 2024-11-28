document.getElementById('descripcion').addEventListener('input', function () {
    const descripcionInput = this.value;
    const regexDescripcion = /^[a-zA-Z0-9\s]+$/;
    const errorMensaje = document.getElementById('error_descripcion');

    if (!regexDescripcion.test(descripcionInput)) {
        errorMensaje.style.display = 'block';
    } else {
        errorMensaje.style.display = 'none';
    }
});
