<?php
require_once("../../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");
session_start();

$idSucursal = $_GET['id_sucursal'] ?? '';
$registros_sucursal = $conexion->query("SELECT descripcion_sucursal, descripcion_complejo FROM sucursal JOIN complejo ON rela_complejo = id_complejo WHERE id_sucursal = $idSucursal");

foreach ($registros_sucursal as $reg) {
    $descripcionSucursal = $reg['descripcion_sucursal'];
    $descripcionComplejo = $reg['descripcion_complejo'];
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Reservas</title>
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

<body>
    <?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php") ?>

    <div class="dashboard">
        <h1>Reservas de Canchas de <?php echo $descripcionSucursal ?></h1>

        <form action="export_pdf.php" method="post" target="_blank">
            <!-- Si usas datos específicos, puedes enviarlos como criterios -->
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
        </form>

        <!-- Gráficos -->
        <div class="charts">
            <div class="chart-container">
                <h2>Dias mas Reservadas</h2>
                <canvas id="reservationsChart"></canvas>
            </div>
            <div class="chart-container">
                <h2>Canchas Más Reservadas</h2>
                <canvas id="popularCourtsChart"></canvas>
            </div>
        </div>

        <!-- Tabla de reservas -->
        <div class="reservations-table">
            <h2>Datos de Reservas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Cancha</th>
                        <th>Total Reservas</th>
                        <th>Finalizados</th>
                        <th>Ausentes</th>
                        <th>Cancelados</th>
                        <th>Recaudación $</th>
                    </tr>
                </thead>
                <tbody id="reservation-data">
                    <!-- Datos cargados dinámicamente -->
                </tbody>
            </table>
        </div>


        <div class="chart-container" style="margin: 0 auto;">
            <h2>Turnos mas Reservados</h2>
            <canvas id="dayNightComparisonChart"></canvas>
        </div>

        <div class="registrosTurnos">
            <table id="tarifasTable" class="table">
                <thead>
                    <tr>
                        <th>Descripción Tarifa</th>
                        <th>Total Reservas</th>
                    </tr>
                </thead>
                <tbody id="tarifasTableBody">
                    <!-- Las filas se generarán dinámicamente -->
                </tbody>
            </table>

        </div>
    </div>

    <script src="../../../js/header.js"></script>
    <script src="../../../js/aside.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filterPeriod = document.getElementById("filter-period");
            const customDateRange = document.getElementById("custom-date-range");
            const startDate = document.getElementById("start-date");
            const endDate = document.getElementById("end-date");
            const applyCustomRangeButton = document.getElementById("apply-custom-range");

            // Mostrar el rango de fechas cuando se selecciona "Personalizado"
            filterPeriod.addEventListener("change", function() {
                if (filterPeriod.value === "custom") {
                    customDateRange.style.display = "block";
                } else {
                    customDateRange.style.display = "none";
                }
            });

            // Acción del botón "Aplicar" para el rango personalizado
            applyCustomRangeButton.addEventListener("click", function(event) {
                event.preventDefault();
                if (startDate.value && endDate.value) {
                    // Puedes agregar la lógica para actualizar los filtros o enviar los datos al servidor
                    console.log("Fecha Desde:", startDate.value, "Fecha Hasta:", endDate.value);
                    alert("Rango personalizado aplicado!");
                } else {
                    alert("Por favor, selecciona un rango válido.");
                }
            });
        });
    </script>

    <script>
        const usuario = <?php echo json_encode($_SESSION['usuario']); ?>;
        const id_Sucursal = <?php echo json_encode($idSucursal); ?>;
        const descripcionsucursal = <?php echo json_encode($descripcionSucursal); ?>;
        const descripcioncomplejo = <?php echo json_encode($descripcionComplejo); ?>;

        $(document).ready(function() {
            // Inicialización de gráficos
            const reservationsChartCtx = document.getElementById('reservationsChart').getContext('2d');
            const popularCourtsChartCtx = document.getElementById('popularCourtsChart').getContext('2d');
            const dayNightComparisonChartCtx = document.getElementById('dayNightComparisonChart').getContext('2d');

            function translateDayToSpanish(day) {
                const daysInSpanish = {
                    Monday: "Lunes",
                    Tuesday: "Martes",
                    Wednesday: "Miércoles",
                    Thursday: "Jueves",
                    Friday: "Viernes",
                    Saturday: "Sábado",
                    Sunday: "Domingo"
                };
                return daysInSpanish[day] || day; // Devuelve el nombre original si no coincide
            }

            let reservationsChart = new Chart(reservationsChartCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Días con más reservas',
                        data: [],
                        backgroundColor: 'rgba(75, 192, 192, 0.5)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            let popularCourtsChart = new Chart(popularCourtsChartCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Reservas por Cancha',
                        data: [],
                        backgroundColor: 'rgba(255, 99, 132, 0.5)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            let dayNightComparisonChart = new Chart(dayNightComparisonChartCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Turnos mas Reservados',
                        data: [], // Valores iniciales
                        backgroundColor: ['rgba(54, 162, 235, 0.5)', 'rgba(255, 206, 86, 0.5)'],
                        borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Función para cargar datos
            function loadData(period = 'week', startDate = null, endDate = null) {
                let requestData = {
                    period,
                    startDate,
                    endDate,
                    id_Sucursal
                };

                $.ajax({
                    url: 'get_reservation_data.php',
                    method: 'GET',
                    data: requestData,
                    success: function(response) {
                        let data;
                        try {
                            data = typeof response === 'string' ? JSON.parse(response) : response;
                        } catch (error) {
                            console.error('Error al parsear JSON:', error);
                            return;
                        }
                        // Procesar los datos obtenidos
                        updateChartsAndTable(data);
                        if (data.tarifas) renderTarifasTable(data.tarifas);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error en la solicitud AJAX:', error);
                    }
                });
            }

            // Actualización de gráficos y tablas
            function updateChartsAndTable(data) {
                // Traducir los días al español
                const translatedDays = data.days.labels.map(translateDayToSpanish);

                // Actualizar gráficos
                reservationsChart.data.labels = translatedDays; // Usar días traducidos
                reservationsChart.data.datasets[0].data = data.days.values || [];
                reservationsChart.update();

                popularCourtsChart.data.labels = data.courts.labels || [];
                popularCourtsChart.data.datasets[0].data = data.courts.Reserva_total || [];
                popularCourtsChart.update();

                // Actualizar gráfico de comparación Día vs Noche
                dayNightComparisonChart.data.labels = data.tarifas.labels || []; // Usar las tarifas como etiquetas
                dayNightComparisonChart.data.datasets[0].data = data.tarifas.total_reserva || []; // O usar 'recaudacion_total' si prefieres mostrar la recaudación
                dayNightComparisonChart.update();


                // Actualizar tabla de reservas
                const tableBody = $('#reservation-data');
                tableBody.empty();
                if (data.courts.labels.length > 0) {
                    data.courts.labels.forEach((court, index) => {
                        tableBody.append(`
                    <tr>
                        <td>${court || 'N/A'}</td>
                        <td>${data.courts.Reserva_total[index] || 0}</td>
                        <td>${data.courts.Finalizados[index] || 0}</td>
                        <td>${data.courts.Ausente[index] || 0}</td>
                        <td>${data.courts.Cancelado[index] || 0}</td>
                        <td>${data.courts.recaudacion_por_cancha[index] || 0}$</td>
                    </tr>
                `);
                    });
                } else {
                    tableBody.append('<tr><td colspan="6">No hay datos disponibles.</td></tr>');
                }
            }

            // Renderizar tabla de tarifas
            function renderTarifasTable(tarifas) {
                const tableBody = $('#tarifasTableBody');
                tableBody.empty();
                tarifas.labels.forEach((label, index) => {
                    tableBody.append(`
                <tr>
                    <td>${label}</td>
                    <td>${tarifas.total_reserva[index]}</td>
                </tr>
            `);
                });
            }

            // Eventos para el filtro
            $('#filter-period').change(function() {
                const period = $(this).val();
                if (period === 'custom') {
                    $('#custom-date-range').show();
                } else {
                    $('#custom-date-range').hide();
                    loadData(period);
                }
            });

            $('#apply-custom-range').click(function() {
                const startDate = $('#start-date').val();
                const endDate = $('#end-date').val();
                if (startDate && endDate && new Date(startDate) <= new Date(endDate)) {
                    loadData('custom', startDate, endDate);
                } else {
                    alert('Por favor, selecciona un rango de fechas válido.');
                }
            });

            // Carga inicial
            loadData();
        });
    </script>

    <script>
        $(document).ready(function() {
            // Evento de exportar a PDF
            $('#exportarpdf').click(function(event) {
                event.preventDefault(); // Prevenir el envío normal del formulario

                // Obtener las gráficas como imágenes
                html2canvas(document.getElementById('reservationsChart')).then(function(canvas) {
                    var reservationsChartImg = canvas.toDataURL(); // Gráfico de reservas por día de la semana
                    html2canvas(document.getElementById('popularCourtsChart')).then(function(canvas) {
                        var popularCourtsChartImg = canvas.toDataURL(); // Gráfico de canchas más reservadas
                        html2canvas(document.getElementById('dayNightComparisonChart')).then(function(canvas) {
                            var dayNightComparisonChartImg = canvas.toDataURL(); // Comparativa Día vs Noche

                            // Obtener los datos de la tabla de reservas
                            var reservationData = [];
                            $('#reservation-data tr').each(function() {
                                var row = [];
                                $(this).find('td').each(function() {
                                    row.push($(this).text());
                                });
                                reservationData.push(row);
                            });

                            // Obtener los datos de la tabla de tarifas
                            var tarifasData = [];
                            $('#tarifasTableBody tr').each(function() {
                                var row = [];
                                $(this).find('td').each(function() {
                                    row.push($(this).text());
                                });
                                tarifasData.push(row);
                            });

                            // Obtener información adicional para el encabezado
                            var fechaHora = new Date().toLocaleString(); // Fecha y hora actual
                            var criterios = $('#filter-period option:selected').text();

                            // Enviar los datos al servidor para generar el PDF
                            $.ajax({
                                url: 'export_pdf.php', // Archivo PHP que generará el PDF
                                method: 'POST',
                                data: {
                                    reservationsChartImg: reservationsChartImg,
                                    popularCourtsChartImg: popularCourtsChartImg,
                                    dayNightComparisonChartImg: dayNightComparisonChartImg,
                                    reservationData: JSON.stringify(reservationData),
                                    tarifasData: JSON.stringify(tarifasData),
                                    fechaHora: fechaHora,
                                    usuario: usuario,
                                    criterios: criterios,
                                    descripcionsucursal: descripcionsucursal,
                                    descripcionComplejo: descripcioncomplejo
                                },
                                success: function(response) {
                                    // Aquí se puede redirigir al archivo PDF o mostrar un mensaje de éxito
                                    var data = JSON.parse(response);
                                    window.open(data.pdfUrl, '_blank'); // Redirige al PDF generado
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error al generar el PDF:', error);
                                }
                            });
                        });
                    });
                });
            });
        });
    </script>
</body>

</html>