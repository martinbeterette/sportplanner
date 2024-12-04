<?php 
	session_start();
    require_once("../../config/root_path.php");
    require_once(RUTA . "config/database/conexion.php");

    if($_SESSION['id_perfil'] == 3) {
    	$id_persona = $_SESSION['id_persona'];
    	$id_usuario = $_SESSION['id_usuario'];
    	$id_sucursal = obtenerSucursal($id_persona, $id_usuario);
    } else {
    	$id_sucursal = $_GET['id_sucursal'] ?? false;
    }

    if ($id_sucursal) {
    	//redirigimos a la seleccion de sucursal
    }


    // Procesar formulario
	 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	    $idDia = $_POST['dia'];
	    $horarioDesde = $_POST['horario_desde'];
	    $horarioHasta = $_POST['horario_hasta'];

	    // Verificar si ya existe itinerario
	    $sqlCheck = "SELECT id_itinerario FROM itinerario WHERE rela_dia = ? AND rela_sucursal = ?";
	    $stmtCheck = $conexion->prepare($sqlCheck);
	    $stmtCheck->bind_param("ii", $idDia, $id_sucursal);
	    $stmtCheck->execute();
	    $resultadoCheck = $stmtCheck->get_result();

	    if ($resultadoCheck->num_rows > 0) {
	        // Si existe, actualizar
	        $sqlUpdate = "
	            UPDATE itinerario
	            SET horario_desde = ?, horario_hasta = ?
	            WHERE rela_dia = ? AND rela_sucursal = ?
	        ";
	        $stmtUpdate = $conexion->prepare($sqlUpdate);
	        $stmtUpdate->bind_param("ssii", $horarioDesde, $horarioHasta, $idDia, $id_sucursal);
	        $stmtUpdate->execute();
	    } else {
	        // Si no existe, insertar
	        $sqlInsert = "
	            INSERT INTO itinerario (horario_desde, horario_hasta, rela_dia, rela_sucursal)
	            VALUES (?, ?, ?, ?)
	        ";
	        $stmtInsert = $conexion->prepare($sqlInsert);
	        $stmtInsert->bind_param("ssii", $horarioDesde, $horarioHasta, $idDia, $id_sucursal);
	        if ($stmtInsert->execute()){
	        	echo "<script defer>swal.fire('todo correcto','','success');</script>";
	        }
	    }
	}


    $sql = "SELECT id_dia,descripcion_dia FROM dia";
    $resultado = $conexion->query($sql);
    $dias_español = [
	    'Monday' => 'Lunes',
	    'Tuesday' => 'Martes',
	    'Wednesday' => 'Miércoles',
	    'Thursday' => 'Jueves',
	    'Friday' => 'Viernes',
	    'Saturday' => 'Sábado',
	    'Sunday' => 'Domingo',
	];

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHECKBOX</title>
    <style type="text/css">
        body {
            /*display: flex;*/
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f3f0f9;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        form {
            margin: auto;
            margin-top: 15vh;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            color: #5e4a8f;
        }
        label {
            display: block;
            margin-top: 15px;
            color: #5e4a8f;
        }
        .perfilXerror {
            margin-top: 5px;
        }
        .inputs {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .inputs:focus {
            border-color: #5e4a8f;
        }
        #modulos div {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        #modulos input[type="checkbox"] {
            margin-right: 10px;
            accent-color: pink;
        }

        .boton-submit {
            margin-top: 20px;
        }
        button {
            background-color: #5e4a8f;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4a3773;
        }
    </style>
</head>

<body>
    <a href="<?php echo BASE_URL. 'index.php'; ?>" class="volver">Volver al inicio</a>
	    <form id="form-itinerario" method="POST">
	    <label for="dia-select">Selecciona un día:</label>
	    <select id="dia-select" name="dia">
	        <option value="" disabled selected>Selecciona un día</option>
	        <?php foreach ($resultado as $reg): ?>
	            <option value="<?php echo $reg['id_dia']; ?>">
	                <?php echo $dias_español[$reg['descripcion_dia']]; ?>
	            </option>
	        <?php endforeach; ?>
	    </select>

	    <label for="horario-desde">Horario Desde:</label>
	    <input type="time" id="horario-desde" name="horario_desde" value="" disabled>

	    <label for="horario-hasta">Horario Hasta:</label>
	    <input type="time" id="horario-hasta" name="horario_hasta" value="" disabled>
	    <div>
	    	<button type="submit" id="boton-submit" name="boton-submit">Guardar</button>
	    	
	    </div>
	</form>
    <script>let id_sucursal = <?php echo json_encode($id_sucursal); ?>;</script>
    <script src="<?php echo BASE_URL . "libs/jquery-3.7.1.min.js" ?>"></script>
    <script src="<?php echo BASE_URL . "libs/sweetalert2.all.min.js" ?>"></script>
	<script>
	    $('#dia-select').change(function() {
	        let idDia = $(this).val();
	        let idSucursal = <?php echo json_encode($id_sucursal); ?>;

	        $.ajax({
	            url: 'ajax/cargar_horarios.php',
	            method: 'GET',
	            data: { id_dia: idDia, id_sucursal: idSucursal },
	            dataType: 'json',
	            success: function(data) {
	                $('#horario-desde').val(data.horario_desde || '').prop('disabled', false);
	                $('#horario-hasta').val(data.horario_hasta || '').prop('disabled', false);
	            },
	            error: function() {
	                console.log('Error al cargar los horarios.');
	            }
	        });
	    });
	</script>


    <div id="resultado-ajax"></div>
    <script src="js/validaciones.js"></script>
</body>
</html>
<?php  
    function obtenerSucursal($id_persona, $id_usuario) {
        global $conexion;

        $sql_sucursal_empleado = "
            SELECT s.id_sucursal 
            FROM empleado e
            JOIN sucursal s ON e.rela_sucursal = s.id_sucursal
            JOIN persona p ON e.rela_persona = p.id_persona
            JOIN contacto c ON p.id_persona = c.rela_persona
            JOIN usuarios u ON c.id_contacto = u.rela_contacto AND u.id_usuario = ?
            WHERE e.rela_persona = ?";

        $stmt_sucursal_empleado = $conexion->prepare($sql_sucursal_empleado);
        $stmt_sucursal_empleado->bind_param("ii", $id_usuario, $id_persona);

        if ($stmt_sucursal_empleado->execute()) {
            $datos_complejo = $stmt_sucursal_empleado->get_result()->fetch_assoc()['id_sucursal'] ?? false;
            return $datos_complejo;
        }
        return false;
    }


?>
<?php $conexion->close(); ?>
