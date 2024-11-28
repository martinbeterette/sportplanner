$(document).ready(function() {
    // Manejar el botón "Siguiente"
    $('#btn-siguiente').on('click', function() {
        $('#paso-1').hide();     // Oculta el paso 1
        $('#paso-2').show();     // Muestra el paso 2
    });
    
    // Manejar el botón "Volver"
    $('#btn-volver').on('click', function() {
        $('#paso-2').hide();     // Oculta el paso 2
        $('#paso-1').show();     // Muestra el paso 1
    });
});
