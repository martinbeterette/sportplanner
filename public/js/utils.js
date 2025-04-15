async function renderTable(url, data = {}, campos = [],page = 1) {
  try {
    const response = await axios.get(url, {
      params: {
        ...data,
        page: page
      }
    });
    // en el success de ajax hacia esto
    const container = document.querySelector('#table-body');
    container.innerHTML = ''; // Limpiar el contenedor antes de agregar nuevos datos

    const paginaActual = response.data.pagina;
    const totalPaginas = response.data.total_paginas;

    response.data.data.forEach(row => {

      const contenido = campos.map(campo => 
        `<td>
          ${row[campo]}
        </td>`
      ).join('');

      container.innerHTML += `
        <tr>
          ${contenido}
        </tr>
      `;
    });
    // Actualizar la paginación
    renderPagination(totalPaginas, paginaActual);
    return true
  } catch (error) {
    console.error("Error al obtener los datos: ", error);
    return [];
  }
};

function renderPagination(total_pages, current_page) {
  let paginacionHTML = `
    <nav aria-label="Paginación">
        <ul class="pagination justify-content-center">
  `;

  // Generar botones de paginación
  for (let i = 1; i <= total_pages; i++) {
    paginacionHTML += `
      <li class="page-item ${i === current_page ? 'active' : ''}">
        <button class="page-link bg-dark text-white border-secondary" data-page="${i}">
          ${i}
        </button>
      </li>
    `;
  }

  document.querySelector('#paginator').innerHTML = paginacionHTML;
}

  
/*$.ajax({
  url: 'ajax/obtenerMembresias.php',
  type: 'GET',
  data: { filtro: filtro, pagina: pagina, id_complejo: id_complejo },
  dataType: 'json',
  success: function(data) {
      // Actualizar el contenedor de la tabla con el HTML generado
      $('#tabla-container').html(data.tabla);
      // Actualizar la paginación
      actualizarPaginacion(data.total_pages, data.current_page);

  console.log(data)
  },
  error: function(xhr, status, error) {
      console.error("Error en la solicitud AJAX: ", status, error);
  }
});*/