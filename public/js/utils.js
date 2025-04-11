async function getData(url, data = {}) {
    try {
      const response = await axios.get(url, { params: data });
      return response.data; // Devuelve el JSON tal como lo recibió
    } catch (error) {
      console.error('Error en getData:', error);
      throw error; // También podés customizar esto si querés devolver algo más "limpio"
    }
}

function renderizarPaginador(paginaActual, totalPaginas, paginasVisibles, contenedorSelector) {
  const contenedor = document.querySelector(contenedorSelector);
  contenedor.innerHTML = ""; // Limpiar paginador anterior

  // Limitar el número de páginas visibles si hay menos páginas totales
  paginasVisibles = Math.min(paginasVisibles, totalPaginas);

  // Calcular inicio y fin
  let mitad = Math.floor(paginasVisibles / 2);
  let inicio = paginaActual - mitad;
  let fin = paginaActual + mitad;

  if (inicio < 1) {
      inicio = 1;
      fin = paginasVisibles;
  }

  if (fin > totalPaginas) {
      fin = totalPaginas;
      inicio = totalPaginas - paginasVisibles + 1;
      if (inicio < 1) inicio = 1;
  }

  // Botón anterior
  if (paginaActual > 1) {
      const btnPrev = document.createElement("button");
      btnPrev.textContent = "« Anterior";
      btnPrev.dataset.pagina = paginaActual - 1;
      contenedor.appendChild(btnPrev);
  }

  // Botones de páginas
  for (let i = inicio; i <= fin; i++) {
      const btn = document.createElement("button");
      btn.textContent = i;
      btn.dataset.pagina = i;
      if (i === paginaActual) {
          btn.classList.add("activo");
      }
      contenedor.appendChild(btn);
  }

  // Botón siguiente
  if (paginaActual < totalPaginas) {
      const btnNext = document.createElement("button");
      btnNext.textContent = "Siguiente »";
      btnNext.dataset.pagina = paginaActual + 1;
      contenedor.appendChild(btnNext);
  }
}