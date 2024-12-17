<?php 
session_start();
require_once("../../../../config/root_path.php");
require_once(RUTA."config/database/conexion.php");
require_once("includes/functions.php");


$id_persona = $_SESSION['id_persona'];
$id_usuario = $_SESSION['id_usuario'];
$id_complejo = obtenerComplejo($id_persona,$id_usuario);
if($id_complejo) {
    $registros = obtenerReservasDelMes($id_complejo);
    $reservas_del_mes = $registros->fetch_assoc()['total'];

    $registros = obtenerCanchasTotales($id_complejo);
    $canchas_totales = $registros->fetch_assoc()['total'];

    $registros = obtenerSucursalesActivas($id_complejo);
    $sucursales_activas = $registros->fetch_assoc()['total'];

    $array_sucursales = $conexion->query("SELECT descripcion_sucursal FROM sucursal JOIN complejo ON rela_complejo = id_complejo WHERE id_complejo = $id_complejo");

} else {
    echo "ha ocurrido un error...";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Propietario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="<?php echo BASE_URL. "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL. "css/aside.css" ?>">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include(RUTA. "includes/header.php") ?>
    <?php include(RUTA. "includes/menu_aside.php") ?>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Dashboard Propietario</h1>

        <!-- Estadísticas globales -->
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Sucursales Activas</h5>
                        <p class="card-text fs-1"><?php echo $sucursales_activas; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Canchas Totales</h5>
                        <p class="card-text fs-1"><?php echo $canchas_totales; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Reservas Mensuales</h5>
                        <p class="card-text fs-1"><?php echo $reservas_del_mes; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ingresos por Sucursal</h5>
                        <canvas id="ingresosChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Reservas por Sucursal</h5>
                        <canvas id="reservasSucursalChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Gestión de Sucursales -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Sucursales</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sucursal</th>
                                    <th>Canchas</th>
                                    <th>Reservas Mensuales</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Sucursal Centro</td>
                                    <td>10</td>
                                    <td>200</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm">Ver Detalles</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Sucursal Norte</td>
                                    <td>8</td>
                                    <td>150</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm">Ver Detalles</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let array_sucursales = <?php echo json_encode($array_sucursales) ?>
    </script>
    <script>
        // Gráfico de Ingresos por Sucursal
        // Obtener el contexto del canvas
        const ingresosCtx = document.getElementById('ingresosChart').getContext('2d');

        // Función para cargar datos dinámicos
        async function cargarGrafico() {
            try {
                // Llama al archivo PHP que devuelve los datos
                const response = await fetch('api/obtener_ingresos.php');
                const data = await response.json();

                if (data.error) {
                    console.error(data.error);
                    return;
                }

                // Extrae etiquetas y valores de los datos
                const labels = data.map(item => item.sucursal);
                const values = data.map(item => parseFloat(item.total));

                // Crea el gráfico dinámicamente
                new Chart(ingresosCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Ingresos (en $)',
                            data: values,
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            borderColor: 'rgba(75, 192, 192, 1)',
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
            } catch (error) {
                console.error('Error al cargar los datos:', error);
            }
        }

        // Llamar a la función para cargar el gráfico
        cargarGrafico();


        // Gráfico de Reservas por Sucursal
        const reservasSucursalCtx = document.getElementById('reservasSucursalChart').getContext('2d');

        async function cargarReservasSucursal() {
            try {
                const response = await fetch('api/obtener_reservas.php');
                
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                
                const data = await response.json();

                if (data.error) {
                    console.error(data.error);
                    return;
                }

                // Extrae etiquetas y valores
                const labels = data.map(item => item.sucursal);
                const values = data.map(item => parseInt(item.total));

                // Crea el gráfico
                new Chart(reservasSucursalCtx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Reservas',
                            data: values,
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(153, 102, 255, 0.6)',
                                'rgba(255, 159, 64, 0.6)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            } catch (error) {
                console.error('Error al cargar los datos:', error);
            }
        }

        // Llama a la función para cargar el gráfico
        cargarReservasSucursal();

    </script>
    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/header.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/aside.js" ?>"></script>
    <script src="<?php echo BASE_URL . "js/terminos_y_condiciones.js" ?>"></script>
</body>
</html>
