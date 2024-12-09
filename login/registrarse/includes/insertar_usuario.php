<?php 

	try {

	    $pdo->beginTransaction(); // Inicia la transacción

	    // Hashear la contraseña
	    $contrasena_hasheada = password_hash($contrasena, PASSWORD_DEFAULT);

	    // Insertar en la tabla `persona`
	    $stmt = $pdo->prepare("INSERT INTO persona (nombre, apellido, rela_sexo, fecha_alta) VALUES (?, ?, ?, CURRENT_DATE())");
	    $stmt->execute([$nombre, $apellido, $sexo]);
	    $id_persona = $pdo->lastInsertId(); // Obtener el ID generado

	    // Insertar en la tabla `documento`
	    $stmt = $pdo->prepare("INSERT INTO documento (descripcion_documento, rela_tipo_documento, rela_persona) VALUES (?, ?, ?)");
	    $stmt->execute([$documento, $tipo_documento, $id_persona]);

	    // Insertar en la tabla `contacto`
	    $stmt = $pdo->prepare("INSERT INTO contacto (descripcion_contacto, rela_persona, rela_tipo_contacto) VALUES (?, ?, ?)");
	    $stmt->execute([$email, $id_persona, 1]);
	    $id_contacto = $pdo->lastInsertId(); // Obtener el ID generado



	    // Insertar en la tabla `usuarios`
	    $stmt = $pdo->prepare("INSERT INTO usuarios (username, password, token, expiry, rela_contacto, rela_perfil) VALUES (?, ?, ?, ?, ?, ?)");
	    $stmt->execute([$username, $contrasena_hasheada, $token, $expiry, $id_contacto, 1]);

	    

	    //SERIA IF($pdo->commit()){ENVIARMAIL()}

	    // Confirmar la transacción
	    if($pdo->commit()) {
	    	if(enviarVerificacion($email,$username,$subject,$message)){
	    		header("Location: ". BASE_URL ."login/inicio_sesion/inicio_sesion.php?correo_enviado");
	    	}
	    }

	} catch (Exception $e) {
	    $pdo->rollBack(); // Revertir cambios en caso de error
	    echo "Error en la transacción: " . $e->getMessage();
	}


?>