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


async function renderizarTablaConPaginador({
  url,
  parametros = {},
  campos = [],
  tablaSelector,
  paginadorSelector,
  paginasVisibles = 5,
  paginaActual = 1
}) {
  try {
    // Agregamos la página actual a los parámetros
    parametros.page = paginaActual;

    // Traer datos del endpoint
    const data = await getData(url, parametros);

    const registros = data.data || data.items || []; // adaptá esto según tu estructura
    const totalPaginas = data.last_page || data.total_pages || 1;

    // Referencias a elementos del DOM
    const tabla = document.querySelector(tablaSelector);
    const tbody = tabla.querySelector("tbody");
    tbody.innerHTML = ""; // Limpiar cuerpo de la tabla

    // Renderizar filas según campos
    registros.forEach(registro => {
      const tr = document.createElement("tr");
      campos.forEach(campo => {
        const td = document.createElement("td");
        td.textContent = registro[campo] ?? "";
        tr.appendChild(td);
      });
      tbody.appendChild(tr);
    });

    // Renderizar paginador
    renderizarPaginador(paginaActual, totalPaginas, paginasVisibles, paginadorSelector);

    // Event delegation para los botones del paginador
    const contenedor = document.querySelector(paginadorSelector);
    contenedor.onclick = (e) => {
      if (e.target.tagName === "BUTTON") {
        const nuevaPagina = parseInt(e.target.dataset.pagina);
        renderizarTablaConPaginador({
          url,
          parametros,
          campos,
          tablaSelector,
          paginadorSelector,
          paginasVisibles,
          paginaActual: nuevaPagina
        });
      }
    };

  } catch (error) {
    console.error("Error al renderizar la tabla:", error);
  }
}