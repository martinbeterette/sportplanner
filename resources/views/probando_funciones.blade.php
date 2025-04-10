<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ejemplo paginado</title>
</head>
<body>
    <h2>Tabla de Posts</h2>
    <div id="table-container"></div>
    <div id="pagination-container" style="margin-top: 1rem;"></div>

    <script src="/resources/js/utils/api.js"></script>
    <script>
            contenido = getData('https://jsonplaceholder.typicode.com/posts');
            if (contenido) {
                querySelector()('#table-container').innerHTML = `
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Contenido</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        ${contenido.map(post => `
                            <tr>
                                <td>${post.id}</td>
                                <td>${post.title}</td>
                                <td>${post.body}</td>
                            </tr>`).join('')}
                    </tbody>
                </table>
                `       
            }
    </script>
</body>
</html>
