<?php

require_once("../../config/root_path.php");
require_once(RUTA . "config/database/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$id_usuario = $_POST['id_usuario'];
	$confirmar_contrasena = $_POST['confirmar-contrasena'];
	$contrasena = $_POST['password'];
	$contrasena_actual = $_POST['contrasena_actual'];
	if ($contrasena != $confirmar_contrasena) {
		header('Location: cambiar_contrasena.php?contrasenas_diferentes');
	}

	$contrasql = $conexion->query("SELECT * FROM usuarios WHERE id_usuario = $id_usuario")->fetch_assoc();

	if (!password_verify($contrasena_actual, $contrasql['password'])) {
		header('Location: cambiar_contrasena.php?contrasena_actual_equivocada');
	}
	$contrasena_hasheada = password_hash($contrasena, PASSWORD_DEFAULT);

	$sql = "UPDATE usuarios SET password = ? WHERE id_usuario = ?";
	$stmt = $conexion->prepare($sql);
	$stmt->bind_param("si", $contrasena_hasheada, $id_usuario);
	if ($stmt->execute()) {
		session_start();
		session_unset();
		session_destroy();
		header("Location: ../../index2.php");
		exit();
	} else {
		echo "Error en el cambio de contraseña";
		echo "<a href='" . BASE_URL . "index2.php'>Inicio</a>";
	}
} else {
	echo "No tiene acceso a este modulo." . "<br>";
	echo "<a href='" . BASE_URL . "index2.php'>Inicio</a>";
}
