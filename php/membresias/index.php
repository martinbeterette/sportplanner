<?php  
    session_start();
    require_once("../../config/root_path.php");
    require_once(RUTA."config/database/conexion.php");
    $id_usuario = $_SESSION['id_usuario'];

    if($_SESSION['id_perfil'] == 23) {
        $id_complejo = obtenerComplejoDelPropietario($id_usuario);
    }

    if($_SESSION['id_perfil'] == 3) {
        $id_complejo = obtenerComplejoDelempleado($id_usuario);
    }
    if(!$id_complejo) {
        header("Location: ". BASE_URL);
    }
    //AUMENTO MASIVO (SI EXISTE ENVIO DEL FORM)
    require_once("includes/aumento_masivo.php");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Membresías</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="<?php echo BASE_URL. "css/header.css" ?>">
    <link rel="stylesheet" href="<?php echo BASE_URL. "css/aside.css" ?>">
</head>
<body>
    <?php include(RUTA . "includes/header.php"); ?>
    <?php include(RUTA . "includes/menu_aside.php"); ?>
    <div class="container">

        <form id="form-actualizar-precios" method="post" align="center">
            <label for="porcentaje">Aumento en porcentaje:(%)</label>
            <input type="number" id="porcentaje" name="porcentaje" min="1" placeholder="Ingrese el porcentaje" required>
            <button 
                type="submit" 
                id="btn-actualizar-precios" 
                name="btn-actualizar-precios"
            >Aplicar Aumento Masivo</button>
        </form>
        
        <h1 align="center">Listado de Membresías</h1>

        <!-- Filtro de búsqueda -->
        <input type="text" id="buscador" placeholder="Buscar por descripción o descuento" />

        <!-- Contenedor de la tabla -->
        <div id="tabla-container"></div>

        <!-- Contenedor de la paginación -->
        <div id="paginacion-container"></div>
        <button class="btn-agregar" id="btn-agregar">Agregar Membresía</button>
    </div>

    <script src="<?php echo BASE_URL.  "libs/jquery-3.7.1.min.js" ?>"></script>
    <script src="<?php echo BASE_URL.  "libs/sweetalert2.all.min.js" ?>"></script>

    <script>let id_complejo = <?php echo json_encode($id_complejo) ?>;</script>
    <script>
        $(() => {
            $(document).on('click', '.btn-agregar', function() {
                window.location.href=`includes/agregar_membresia.php?id_complejo=${id_complejo}`;
            });
        });
    </script>
    <script src="js/tablaYPaginado.js"></script>
    <script src="<?php echo BASE_URL ?>js/header.js"></script>
    <script src="<?php echo BASE_URL ?>js/aside.js"></script>
    <script>
        $(document).on("click",".eliminar", function() {
            let id_membresia = $(this).attr('valor');
            window.location.href = `includes/eliminar_membresia.php?id_membresia=${id_membresia}`;
        });
    </script>
</body>
</html>

<?php    

    function obtenerComplejoDelPropietario($id_usuario) {
        global $conexion;
        $sql = "
            SELECT rela_complejo
            FROM asignacion_persona_complejo apc 
            WHERE apc.rela_usuario = ?
        ";

        $stmt_sucursales_propietario = $conexion->prepare($sql);
        $stmt_sucursales_propietario->bind_param("i",$id_usuario);
        if($stmt_sucursales_propietario->execute()){
            $registros = $stmt_sucursales_propietario->get_result()->fetch_assoc()['rela_complejo'];
            return $registros;
        }
        return false;
    }

    function obtenerComplejoDelempleado($id_usuario) {
        global $conexion;
        $sql = "
            SELECT s.rela_complejo
            FROM empleado e 
            JOIN sucursal s ON s.id_sucursal = e.rela_sucursal
            WHERE e.rela_usuario = ?
        ";

        $stmt_sucursales_propietario = $conexion->prepare($sql);
        $stmt_sucursales_propietario->bind_param("i",$id_usuario);
        if($stmt_sucursales_propietario->execute()){
            $registros = $stmt_sucursales_propietario->get_result()->fetch_assoc()['rela_complejo'];
            return $registros;
        }
        return false;
    }
?>