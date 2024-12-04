<?php
require_once("../../config/database/conexion.php");
session_start();
$usuario	= $_POST['username'];
$id_usuario = $_SESSION['id_usuario'];

$sql_usuario = "UPDATE usuarios SET 
								username = ?
							WHERE 
								id_usuario = ?";

$stmt1 = $conexion->prepare($sql_usuario);
$stmt1->bind_param("si", $usuario, $id_usuario);

if ($stmt1->execute()) {
	unset($_SESSION['usuario']);
	$_SESSION['usuario'] = $usuario;
	header("Location: mis_datos.php");
} else {
	echo "error en el update de usuario";
}
