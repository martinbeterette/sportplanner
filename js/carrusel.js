$(document).ready(function () {
    // Seleccionamos todas las imágenes del carrusel
    let images = $('.carousel-slide img');
    let currentIndex = 0;

    // Funcion para mostrar la siguiente imagen
    function showNextImage() {
        // Ocultamos la imagen actual
        images.eq(currentIndex).removeClass('active');
        // Pasamos a la siguiente imagen
        currentIndex = (currentIndex + 1) % images.length;
        // Mostramos la nueva imagen
        images.eq(currentIndex).addClass('active');
    }

    // Cambiamos la imagen cada 7 segundos
    setInterval(showNextImage, 7000);

    // Mostramos la primera imagen cuando se carga la página
    images.eq(currentIndex).addClass('active');
});
