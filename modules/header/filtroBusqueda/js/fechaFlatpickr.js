$(document).ready(function () {
    flatpickr("#fecha", {
        dateFormat: "Y-m-d", // Formato de fecha almacenado
        minDate: "today", // Hoy como la fecha mínima
        maxDate: new Date().fp_incr(7), // 7 días hacia adelante desde hoy
        defaultDate: "today", // Preselecciona la fecha de hoy
        altInput: true,
        altFormat: "F j, Y", // Formato alternativo que se muestra
        allowInput: false // Evita que el usuario escriba manualmente
    });
}); //document ready