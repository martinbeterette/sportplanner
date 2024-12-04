<?php
require_once("../../config/database/conexion.php");
session_start();


$nombre 	= $_POST['nombre'];
$apellido 	= $_POST['apellido'];
$documento 	= $_POST['documento'];
$sexo 		= (int)$_POST['sexo'];
$email      = $_SESSION['email'];

// echo $nombre."<br>".$apellido."<br>".$documento."<br>".$sexo."<br>".$email."<br>";die;

$sql_persona = "UPDATE 
								persona
							JOIN
								contacto 
							ON 
								contacto.rela_persona = persona.id_persona
							SET 
								nombre 		= ?, 
								apellido 	= ?,
								rela_sexo 	= ?
							WHERE 
								contacto.descripcion_contacto LIKE ?";

$sql_documento = "UPDATE 
								documento
							JOIN
								persona
							ON
								persona.id_persona = documento.rela_persona
							JOIN
								contacto
							ON
								contacto.rela_persona = persona.id_persona
							SET 
								descripcion_documento = ?
							WHERE 
								contacto.descripcion_contacto LIKE ?";

$stmt1 = $conexion->prepare($sql_persona);
$stmt2 = $conexion->prepare($sql_documento);

$stmt1->bind_param("ssis", $nombre, $apellido, $sexo, $email);
$stmt2->bind_param("ss", $documento, $email);


if ($stmt1->execute() && $stmt2->execute()) {
	unset($_SESSION['datos_personales']);
	header("Location: mis_datos.php");
} else {
	echo "error en el update de persona" . $conexion->error;
}
$stmt1->close();
$stmt2->close();
$conexion->close();
