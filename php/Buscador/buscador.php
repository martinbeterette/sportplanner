<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscador con AJAX y Paginación</title>
    <style>
        .pagination {
            margin-top: 20px;
        }
        .pagination button {
            margin: 0 2px;
            padding: 5px 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <input type="text" id="search-input" placeholder="Buscar...">
    <div id="table-container"></div>
    <div class="pagination" id="pagination"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Función para obtener resultados desde el servidor
            function fetchResults(search, page) {
                $.ajax({
                    url: 'buscar.php', // Aquí va la URL de tu archivo PHP
                    method: 'GET',
                    data: {
                        search: search,
                        page: page
                    },
                    dataType: 'json',
                    success: function(response) {
                        renderTable(response.data);
                        renderPagination(response.total_pages, response.current_page);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error en la solicitud AJAX: " + error);
                    }
                });
            }

            // Función para renderizar la tabla con los resultados
            function renderTable(data) {
                var html = '<table>';
                html += '<thead><tr><th>ID</th><th>Nombre</th><th>Descripción</th></tr></thead>';
                html += '<tbody>';
                $.each(data, function(index, item) {
                    html += '<tr>';
                    html += '<td>' + item.id + '</td>';
                    html += '<td>' + item.name + '</td>';
                    html += '<td>' + item.description + '</td>';
                    html += '</tr>';
                });
                html += '</tbody>';
                html += '</table>';
                $('#table-container').html(html);
            }

            // Función para renderizar los botones de paginación
            function renderPagination(totalPages, currentPage) {
                var paginationHtml = '';
                for (var i = 1; i <= totalPages; i++) {
                    paginationHtml += '<button class="page-btn" data-page="' + i + '">' + i + '</button>';
                }
                $('#pagination').html(paginationHtml);

                // Manejo de clicks en los botones de paginación
                $('.page-btn').on('click', function() {
                    var page = $(this).data('page');
                    fetchResults($('#search-input').val(), page);
                });
            }

            // Manejo del input de búsqueda
            $('#search-input').on('input', function() {
                fetchResults($(this).val(), 1);
            });

            // Cargar resultados iniciales
            fetchResults('', 1);
        });
    </script>
</body>
</html>
