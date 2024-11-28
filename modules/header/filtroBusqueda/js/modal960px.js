// Función para abrir el modal
function openModal() {
    document.getElementById('filter-modal').style.display = 'flex';

    // Mover el formulario al modal
    const form = document.getElementById('filtro_deporte');
    document.getElementById('filtro_deporte_modal').appendChild(form);

    // Asegurarse de que el formulario se muestre
    form.style.display = 'flex'; // o 'block' si lo prefieres
}

// Función para cerrar el modal
function closeModal() {
    document.getElementById('filter-modal').style.display = 'none';

    // Opcional: Puedes volver a ocultar el formulario si deseas
    const form = document.getElementById('filtro_deporte');
    form.style.display = 'none';
}
