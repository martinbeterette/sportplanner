document.getElementById('fecha_nacimiento').addEventListener('change', function () {
    const inputDate = new Date(this.value);
    const today = new Date();

    // Restar 18 años a la fecha actual
    const minDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());

    const errorMessage = document.getElementById('error_message');

    // Comparar si la fecha ingresada es mayor a la fecha mínima permitida
    if (inputDate > minDate) {
        // Mostrar el mensaje de error
        errorMessage.style.display = 'block';
        this.value = '';  // Limpiar el campo de entrada
    } else {
        // Ocultar el mensaje de error si la fecha es válida
        errorMessage.style.display = 'none';
    }
});