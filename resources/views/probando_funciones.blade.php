<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ejemplo paginado</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        #pagination-container button {
            margin: 0 2px;
            padding: 5px 10px;
        }

        #pagination-container .activo {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h2>Tabla de asd</h2>
    <div id="mi-tabla">
        <table>
            <thead>
                <th>nombre</th>
                <th>apellido</th>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div id="paginador" style="margin-top: 1rem;"></div>

    <script src="{{ asset('js/utils.js') }}"></script>
   
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        renderizarTablaConPaginador({
          url: '/api/personas',
          parametros: {}, // podés poner filtros o lo que quieras
          campos: ['nombre', 'apellido'],
          tablaSelector: '#miTabla',
          paginadorSelector: '#paginador'
        });
      });
    </script>
    
</body>
</html>
