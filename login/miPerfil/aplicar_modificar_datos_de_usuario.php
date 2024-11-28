<?php 
		require_once("../../config/database/conexion.php"); 
		session_start();
		$email 		= $_POST['email'];
		$usuario	= $_POST['username'];
		$id_usuario = $_SESSION['id_usuario'];

		$sql_usuario = "UPDATE usuarios SET 
								username = ?
							WHERE 
								id_usuario = ?";

		$sql_contacto = "UPDATE 
								contacto 
							JOIN
								usuarios
							ON
								contacto.id_contacto = usuarios.rela_contacto
							SET 
								descripcion_contacto = ?
							WHERE 
								id_usuario = ?";

		$stmt1 = $conexion->prepare($sql_usuario);
		$stmt1->bind_param("si", $usuario, $id_usuario);
		$stmt2 = $conexion->prepare($sql_contacto);
		$stmt2->bind_param("si", $email, $id_usuario);

		if($stmt1->execute() && $stmt2->execute()) {
			header("Location: mis_datos.php");
		} else {
			echo "error en el update de usuario";
		}
?>