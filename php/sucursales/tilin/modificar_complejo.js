function mostrarPaso(paso) {
    const pasos = document.querySelectorAll('.paso');
    pasos.forEach((p, index) => {
        p.style.display = (index + 1 === paso) ? 'flex' : 'none';
    });
}

// Inicializa el paso 1 al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    mostrarPaso(3);
});

document.getElementById('formulario-multipasos').addEventListener('submit', function (e) {
    e.preventDefault();
    alert('Formulario enviado con éxito.');
});
