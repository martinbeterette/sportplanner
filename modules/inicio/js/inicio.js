const images = [
    '../../../assets/bienvenida/fut.jpg',
    '../../../assets/bienvenida/nba.jpg',
    '../../../assets/bienvenida/rugby.jpg'
];

let currentIndex = 0;

function changeBackground() {
    document.querySelector('.parte2').style.backgroundImage = `url(${images[currentIndex]})`;
    currentIndex = (currentIndex + 1) % images.length;
}

// Cambia la imagen de fondo cada 5 segundos
setInterval(changeBackground, 5000);

// Inicia el carrusel con la primera imagen
changeBackground();