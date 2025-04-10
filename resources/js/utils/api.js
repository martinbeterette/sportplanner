export async function getData(url, data = {}) {
    try {
      const response = await axios.get(url, { params: data });
      return response.data; // Devuelve el JSON tal como lo recibió
    } catch (error) {
      console.error('Error en getData:', error);
      throw error; // También podés customizar esto si querés devolver algo más "limpio"
    }
}