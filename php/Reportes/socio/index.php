<?php
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Complejo Deportivo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://kit.fontawesome.com/03cc0c0d2a.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link rel="stylesheet" href="../../../css/header.css">
    <link rel="stylesheet" href="../../../css/aside.css">
    <link rel="stylesheet" href="style.css">
</head>

<?php include(RUTA . "includes/header.php"); ?>
<?php include(RUTA . "includes/menu_aside.php") ?>

<body>
    <div class="dashboard">
        <h1>Reporte de Socios y Membresías</h1>

        <div class="filtroyexport">
            <div class="filters">
                <label for="filter-period">Periodo:</label>
                <select id="filter-period">
                    <option value="day">Día</option>
                    <option value="week">Semana</option>
                    <option value="month">Mes</option>
                    <option value="year">Año</option>
                    <option value="custom">Personalizado</option>
                </select>
                <div id="custom-date-range" style="display: none;">
                    <label for="start-date">Desde:</label>
                    <input type="date" id="start-date">
                    <label for="end-date">Hasta:</label>
                    <input type="date" id="end-date">
                    <button id="apply-custom-range">Aplicar</button>
                </div>
            </div>
            <button type="submit" id="exportarpdf">Exportar a PDF</button>
        </div>

        <!-- Gráficos -->
        <div class="charts">
            <div class="chart-container">
                <h2>Socios con Más Reservas</h2>
                <canvas id="sociosChart"></canvas>
            </div>
            <div class="chart-container">
                <h2>Membresías Más Solicitadas</h2>
                <canvas id="membresiasChart"></canvas>
            </div>
        </div>

        <!-- Tablas -->
        <div class="tablacontainer">
            <h2 style="text-align: center;">Socios con Más Reservas</h2>
            <table id="detalleSocios" class="tabla-datos">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Membresia</th>
                        <th>Fecha Afiliacion</th>
                        <th>Fecha Expiracion</th>
                        <th>Total de Reservas</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Datos generados dinámicamente -->
                </tbody>
            </table>
        </div>

        <div class="tablacontainer">
            <h2 style="text-align: center;">Membresías Más Solicitadas</h2>
            <table id="detalleMembresias" class="tabla-datos">
                <thead>
                    <tr>
                        <th>Membresía</th>
                        <th>Descuento %</th>
                        <th>Precio Membresia $</th>
                        <th>Total de Socios</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <script src="../../../libs/jquery-3.7.1.min.js"></script>
    <script src="../../../js/header.js"></script>
    <script src="../../../js/aside.js"></script>

    <script>
        function fetchDatosReporte() {
            const fechaDesde = document.getElementById("start-date").value;
            const fechaHasta = document.getElementById("end-date").value;

            $.ajax({
                url: "api_reservas.php", // Archivo PHP que devuelve datos para el reporte
                method: "GET",
                data: {
                    fechaDesde,
                    fechaHasta,
                },
                dataType: "json",
                success: function(response) {
                    generarTablas(response);
                    actualizarGraficos(response);
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar los datos:", error);
                },
            });
        }

        function generarTablas(datos) {
            // Generar tabla de socios
            const tablaSocios = document.querySelector("#detalleSocios tbody");
            tablaSocios.innerHTML = ""; // Limpiar la tabla

            datos.socios.forEach((item) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${item.nombre}</td>
                    <td>${item.apellido}</td>
                    <td>${item.descripcion_documento}</td>
                    <td>${item.descripcion_membresia}</td>
                    <td>${item.fecha_afiliacion}</td>
                    <td>${item.fecha_expiracion}</td>
                    <td>${item.total_reservas}</td>
                `;
                tablaSocios.appendChild(row);
            });

            // Generar tabla de membresías
            const tablaMembresias = document.querySelector("#detalleMembresias tbody");
            tablaMembresias.innerHTML = ""; // Limpiar la tabla

            datos.membresias.forEach((item) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${item.descripcion_membresia}</td>
                    <td>${item.descuento}%</td>
                    <td>${item.precio_membresia}$</td>
                    <td>${item.total_socios}</td>
                `;
                tablaMembresias.appendChild(row);
            });
        }

        function actualizarGraficos(datos) {
            const ctxSocios = document.getElementById("sociosChart").getContext("2d");
            const ctxMembresias = document.getElementById("membresiasChart").getContext("2d");

            // Gráfico de Socios
            new Chart(ctxSocios, {
                type: "bar",
                data: {
                    labels: datos.socios.map((item) => item.nombre),
                    datasets: [{
                        label: "Socios con mas Reservas",
                        data: datos.socios.map((item) => item.total_reservas),
                        backgroundColor: "rgba(255, 99, 132, 0.5)",
                        borderColor: "rgba(255, 99, 132, 1)",
                        borderWidth: 1,
                    }, ],
                },
                options: {
                    responsive: true,
                    indexAxis: "y",
                },
            });

            // Gráfico de Membresías
            new Chart(ctxMembresias, {
                type: "bar",
                data: {
                    labels: datos.membresias.map((item) => item.descripcion_membresia),
                    datasets: [{
                        label: "Solicitudes",
                        data: datos.membresias.map((item) => item.total_socios),
                        backgroundColor: "rgba(54, 162, 235, 0.5)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        borderWidth: 1,
                    }, ],
                },
                options: {
                    responsive: true,
                },
            });
        }

        document.getElementById("apply-custom-range").addEventListener("click", fetchDatosReporte);

        // Cargar datos al inicio
        fetchDatosReporte();
    </script>

    <script>
        const tablaSocios = document.querySelector("#detalleSocios tbody");
        const tablaMembresias = document.querySelector("#detalleMembresias tbody");
        const ctxSocios = document.getElementById("sociosChart").getContext("2d");
        const ctxMembresias = document.getElementById("membresiasChart").getContext("2d");
        $.ajax({
            url: "exportar_pdf.php",
            method: "POST",
            data: {
                tablaSocios,
                tablaMembresias,
                ctxSocios,
                ctxMembresias,
            },
            dataType: "json",
            success: function(response) {
                if (response.filePath) {
                    const link = document.createElement("a");
                    link.href = response.filePath;
                    link.download = "reporte_socios_membresias.pdf";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                } else {
                    console.error("Error: No se pudo generar el PDF.");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al exportar PDF:", error);
            },
        });
    </script>
</body>

</html>