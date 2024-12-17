<?php
require_once("../../../../config/root_path.php");
require_once(RUTA. "config/database/conexion.php");
require_once(RUTA. "config/database/db_functions/personas.php");
session_start();
// Suponiendo que ya tienes conexión a la base de datos almacenada en $conexion
//paso 1
$descripcion_complejo       = $_POST['descripcion_complejo'];
$fecha_fundacion_complejo   = $_POST['fecha_fundacion_complejo'];

//paso 2
$descripcion_sucursal       = $_POST['descripcion_sucursal'];
$fecha_fundacion_sucursal   = $_POST['fecha_fundacion_sucursal'];
$rela_barrio                = $_POST['barrio']; // Este valor viene del formulario
$direccion                  = $_POST['direccion'];


//paso 3
$email = $_SESSION['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$rela_contacto = $_SESSION['id_email'];
$fecha_creacion_usuario = date('Y-m-d');

require_once("validar_formulario.php");

require_once("insertar_complejo_propietario.php");

?>
