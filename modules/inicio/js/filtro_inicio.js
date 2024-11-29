function updateTipoDeporte() {
    const deporteSelect = document.getElementById('deporte');
    const tipoDeporteSelect = document.getElementById('tipoDeporte');
    const tipoDeporteContainer = document.getElementById('tipo-deporte-container');

    // Limpiar las opciones anteriores
    tipoDeporteSelect.innerHTML = '';

    // Mostrar opciones según el deporte seleccionado
    if (deporteSelect.value === 'futbol') {
        tipoDeporteContainer.style.display = 'block';
        tipoDeporteSelect.innerHTML = `
            <option value="futbol5">Fútbol 5</option>
            <option value="futbol7">Fútbol 7</option>
            <option value="futbol11">Fútbol 11</option>
        `;
    } else if (deporteSelect.value === 'voley') {
        tipoDeporteContainer.style.display = 'block';
        tipoDeporteSelect.innerHTML = `
            <option value="voleyIndoor">Vóley Indoor</option>
            <option value="voleyPlaya">Vóley Playa</option>
        `;
    } else if (deporteSelect.value === 'basquet') {
        tipoDeporteContainer.style.display = 'block';
        tipoDeporteSelect.innerHTML = `
            <option value="basquet5x5">Básquet 5x5</option>
            <option value="basquet3x3">Básquet 3x3</option>
        `;
    } else {
        tipoDeporteContainer.style.display = 'none';
    }
}

document.getElementById('filtro_deporte').addEventListener('submit', function (event) {
    event.preventDefault();

    const deporte = document.getElementById('deporte').value;
    const tipoDeporte = document.getElementById('tipoDeporte').value;
    const superficie = document.getElementById('superficie').value;
    const fecha = document.getElementById('fecha').value;
    const hora = document.getElementById('hora').value;

    // Validación básica
    if (!deporte || !superficie || !fecha || !hora) {
        alert('Por favor, completa todos los campos antes de buscar.');
        return;
    }

    // Simulación de búsqueda
    console.log(`Buscando partidos de ${deporte} (${tipoDeporte}), en superficie de ${superficie}, el día ${fecha} a las ${hora}.`);
    alert(`Buscando partidos de ${deporte} (${tipoDeporte}), en superficie de ${superficie}, el día ${fecha} a las ${hora}.`);
});