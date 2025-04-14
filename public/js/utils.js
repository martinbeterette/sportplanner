async function renderTable(url, data = {}, campos = [],page = 1) {
  try {
    const response = await axios.get(url, {
      params: {
        ...data,
        page: page
      }
    });
    // en el success de ajax hacia esto
    const container = document.querySelector('#tabla-container');

    response.data.data.forEach(row => {

      const contenido = campos.map(campo => row[campo]).join(' - ');
      container.innerHTML += `
        <div>
          ${contenido}
        </div>
      `;
    });
    // Actualizar la paginación
    // renderPagination(data.total_pages, data.current_page);
    return true
  } catch (error) {
    console.error("Error al obtener los datos: ", error);
    return [];
  }
};

function renderPagination(total_pages, current_page) {
  var paginacionHTML = '';

  // Generar botones de paginación
  for (var i = 1; i <= total_pages; i++) {
      if (i === current_page) {
          paginacionHTML += '<span class="pagina-activa">' + i + '</span>';
      } else {
          paginacionHTML += '<button class="pagina-boton" data-page="' + i + '">' + i + '</button>';
      }
  }

  $('#paginacion-container').html(paginacionHTML);
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