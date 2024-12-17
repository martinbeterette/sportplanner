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
    <link rel="stylesheet" href="<?php echo BASE_URL . "css/footer.css" ?>">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php") ?>

    <div class="dashboard">
        <h1>Reporte del Complejo <?php  ?></h1>

        <!-- Filtros -->
        <div class="filters">
            <label for="fechaDesde">Desde:</label>
            <input type="date" id="fechaDesde">
            <label for="fechaHasta">Hasta:</label>
            <input type="date" id="fechaHasta">
            <button id="aplicarFiltros">Aplicar Filtros</button>
        </div>
        <button type="submit" id="exportarpdf">Exportar a PDF</button>

        <!-- Gráficos -->
        <div class="charts">
            <div class="chart-container">
                <h2>Reservas por Sucursal</h2>
                <canvas id="reservasChart"></canvas>
            </div>
            <div class="chart-container">
                <h2>Ganancias por Sucursal</h2>
                <canvas id="gananciasChart"></canvas>
            </div>
        </div>

        <!-- Tablas -->
        <div class="tablacontainer">
            <h2 style="text-align: center;">Reservas por Sucursal</h2>
            <table id="detalleReservas" class="tabla-datos">
                <thead>
                    <tr>
                        <th>Sucursal</th>
                        <th>Total de Reservas</th>
                        <th>Canceladas</th>
                        <th>Ausentes</th>
                        <th>Finalizadas</th>
                        <th>Ganancias ($)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Datos generados dinámicamente -->
                </tbody>
            </table>
        </div>

        <div class="tablacontainer">
            <h2 style="text-align: center;">Empleados por Sucursal</h2>
            <table id="detalleEmpleados" class="tabla-datos">
                <thead>
                    <tr>
                        <th>Sucursal</th>
                        <th>Total Empleados</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <?php include(RUTA . "includes/footer.php") ?>

    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "libs/sweetalert2.all.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/header.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/terminoscondiciones.js" ?>"></script>

    <script>
        function fetchDatosDashboard() {
            const fechaDesde = document.getElementById("fechaDesde").value;
            const fechaHasta = document.getElementById("fechaHasta").value;

            $.ajax({
                url: "get_data.php",
                method: "GET",
                data: {
                    fechaDesde,
                    fechaHasta
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
            // Generar tabla de reservas
            const tablaReservas = document.querySelector("#detalleReservas tbody");
            tablaReservas.innerHTML = ""; // Limpiar la tabla

            datos.forEach((item) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                <td>${item.sucursal}</td>
                <td>${item.Reserva_total}</td>
                <td>${item.Cancelado}</td>
                <td>${item.Ausente}</td>
                <td>${item.Finalizados}</td>
                <td>$${item.recaudacion_por_sucursal}</td>
            `;
                tablaReservas.appendChild(row);
            });

            // Generar tabla de empleados
            const tablaEmpleados = document.querySelector("#detalleEmpleados tbody");
            tablaEmpleados.innerHTML = ""; // Limpiar la tabla

            datos.forEach((item) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                <td>${item.sucursal}</td>
                <td>${item.total_empleado}</td>
            `;
                tablaEmpleados.appendChild(row);
            });
        }

        function actualizarGraficos(datos) {
            const ctxReservas = document.getElementById("reservasChart").getContext("2d");
            const ctxGanancias = document.getElementById("gananciasChart").getContext("2d");

            // Gráfico de Reservas
            new Chart(ctxReservas, {
                type: "bar",
                data: {
                    labels: datos.map((item) => item.sucursal),
                    datasets: [{
                        label: "Total de Reservas",
                        data: datos.map((item) => item.Reserva_total),
                        backgroundColor: "rgba(54, 162, 235, 0.5)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        borderWidth: 1,
                    }],
                },
                options: {
                    responsive: true,
                    indexAxis: "y"
                },
            });

            // Gráfico de Ganancias
            new Chart(ctxGanancias, {
                type: "bar",
                data: {
                    labels: datos.map((item) => item.sucursal),
                    datasets: [{
                        label: "Ganancias Totales ($)",
                        data: datos.map((item) => item.recaudacion_por_sucursal),
                        backgroundColor: "rgba(75, 192, 192, 0.5)",
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 1,
                    }],
                },
                options: {
                    responsive: true,
                    indexAxis: "y"
                },
            });
        }

        document.getElementById("aplicarFiltros").addEventListener("click", fetchDatosDashboard);

        // Cargar datos al inicio
        fetchDatosDashboard();
    </script>

    <script>
        document.getElementById("exportarpdf").addEventListener("click", async () => {
            // Capturar gráficos como imágenes
            const reservasChart = await html2canvas(document.getElementById("reservasChart")).then((canvas) =>
                canvas.toDataURL("image/png")
            );
            const gananciasChart = await html2canvas(document.getElementById("gananciasChart")).then((canvas) =>
                canvas.toDataURL("image/png")
            );

            // Capturar datos de las tablas
            const reservasData = [];
            document.querySelectorAll("#detalleReservas tbody tr").forEach((row) => {
                const cells = row.querySelectorAll("td");
                reservasData.push({
                    sucursal: cells[0].innerText,
                    total: cells[1].innerText,
                    canceladas: cells[2].innerText,
                    ausentes: cells[3].innerText,
                    finalizadas: cells[4].innerText,
                    ganancias: cells[5].innerText,
                });
            });

            const empleadosData = [];
            document.querySelectorAll("#detalleEmpleados tbody tr").forEach((row) => {
                const cells = row.querySelectorAll("td");
                empleadosData.push({
                    sucursal: cells[0].innerText,
                    empleados: cells[1].innerText,
                });
            });

            // Enviar datos al servidor
            const response = await fetch("generate_pdf.php", {
                method: "POST",
                body: JSON.stringify({
                    reservasChart,
                    gananciasChart,
                    reservasData,
                    empleadosData,
                }),
                headers: {
                    "Content-Type": "application/json",
                },
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = URL.createObjectURL(blob);

                // Abrir el PDF en una nueva pestaña
                window.open(url, '_blank');
            } else {
                console.error("Error al generar el PDF");
            }
        });
    </script>
</body>

</html>