<?php
// Conexión a la base de datos
require_once("../../../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");

// Obtener el id_sucursal de la URL
$id_sucursal = $_GET['id_sucursal'] ?? die("falta get de sucursal");
$id_complejo = $_GET['id_complejo'] ?? die("falta get de complejo");
if(!is_numeric($id_sucursal) || !is_numeric($id_complejo)) {
    echo "parametros invalidos";
}

// Obtener los datos de la sucursal
$query = "SELECT sucursal.id_sucursal, sucursal.descripcion_sucursal, sucursal.fecha_de_creacion,
               asignacion_sucursal_domicilio.id_asignacion_sucursal_domicilio, asignacion_sucursal_domicilio.direccion,
               barrio.id_barrio, barrio.descripcion_barrio, id_localidad, localidad.rela_provincia
        FROM sucursal
        JOIN asignacion_sucursal_domicilio ON asignacion_sucursal_domicilio.rela_sucursal = sucursal.id_sucursal
        JOIN barrio ON barrio.id_barrio = asignacion_sucursal_domicilio.rela_barrio
        JOIN localidad ON barrio.rela_localidad = localidad.id_localidad
        WHERE sucursal.id_sucursal = $id_sucursal";
$registros_sucursal = $conexion->query($query)->fetch_assoc();
$descripcion_sucursal = $registros_sucursal['descripcion_sucursal'];
$direccion = $registros_sucursal['direccion'];
$fecha_de_creacion = $registros_sucursal['fecha_de_creacion'];
$barrio = $registros_sucursal['id_barrio'];
$localidad = $registros_sucursal['id_localidad'];
$provincia = $registros_sucursal['rela_provincia'];


// Obtener los valores para los selects
$query_barrios = "SELECT * FROM barrio";
$result_barrios = $conexion->query($query_barrios);

$query_localidades = "SELECT * FROM localidad";
$result_localidades = $conexion->query($query_localidades);

$query_provincias = "SELECT * FROM provincia";
$result_provincias = $conexion->query($query_provincias);

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Sucursal</title>
    <link rel="stylesheet" href="../css/formulario_modificacion.css"> <!-- Si estás usando Bootstrap -->
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Modificar Sucursal</h1>
        </div>

        <!-- Formulario de modificación -->
        <div class="form-container">
            <h2>Modificar los detalles de la sucursal</h2>
            <form action="guardar_modificacion.php" method="POST" id="formulario">
                <input type="hidden" name="id_sucursal" value="<?php echo $id_sucursal; ?>">
                <input type="hidden" name="id_complejo" value="<?php echo $id_complejo; ?>">

                <!-- Descripción de la sucursal -->
                <div class="form-group">
                    <label for="descripcion_sucursal">Descripción de la Sucursal</label>
                    <input type="text" id="descripcion_sucursal" name="descripcion_sucursal" value="<?php echo $descripcion_sucursal; ?>" required>
                </div>

                <!-- Dirección -->
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" id="direccion" name="direccion" value="<?php echo $direccion; ?>" required>
                </div>

                <!-- Barrio -->
                <div class="form-group">
                    <label for="barrio">Barrio</label>
                    <select id="barrio" name="barrio" required>
                       
                    </select>
                </div>

                <!-- Localidad -->
                <div class="form-group">
                    <label for="localidad">Localidad</label>
                    <select id="localidad" name="localidad" required>
                      
                    </select>
                </div>

                <!-- Provincia -->
                <div class="form-group">
                    <label for="provincia">Provincia</label>
                    <select id="provincia" name="provincia" required>

                    </select>
                </div>

                <!-- Fecha de creación -->
                <div class="form-group">
                    <label for="fecha_de_creacion">Fecha de Creación</label>
                    <input type="date" id="fecha_de_creacion" name="fecha_de_creacion" value="<?php echo $fecha_de_creacion; ?>" >
                </div>

                <!-- Botón de submit -->
                <div class="form-btn-container">
                    <button type="submit" class="form-btn">Modificar Sucursal</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        let provincia_seleccionada  = <?php echo $provincia; ?>;
        let localidad_seleccionada  = <?php echo $localidad; ?>;
        let barrio_seleccionado     = <?php echo $barrio ?>;
    </script>
    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js"; ?>"></script>
    <script src="<?php echo BASE_URL . "libs/sweetalert2.all.min.js"; ?>"></script>
    <script src="../js/obtener_domicilio.js"></script>
    <script>
        $(document).ready(function() {
            $('.form-btn').on("click", function(event) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: '¿Seguro que quieres modificar este registro?',
                    text: 'No podrás deshacer los cambios',
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonText: 'Sí, Seguro'
                }).then((result) => {
                    if (result.isConfirmed) {
                        
                        // o enviar el formulario
                        $('#formulario').submit();  // Si decides proceder, envías el formulario
                    }
                });
            });
        });
    </script>
</body>
</html>


