<?php
require_once('../config/database/conexion.php');
$username    = $_POST['username'];
$password    = $_POST['password'];

//echo $user;
//echo $name;

$sql="SELECT 
            usuarios.id_usuario,
            usuarios.password
        FROM 
            usuarios
        JOIN
            contacto
        ON
            usuarios.rela_contacto = contacto.id_contacto
        WHERE 
            usuarios.username LIKE '{$username}'
        AND
            usuarios.estado LIKE 'verificado'";

$datos = $conexion->query($sql);

// el compañero usa mysql->query 
if($datos->num_rows == 1) {

    $row = $datos->fetch_assoc();

    if(password_verify($password, $row['password'])) {

        $sql = "SELECT id_usuario ,username ,id_perfil, descripcion_perfil, id_contacto,descripcion_contacto, id_persona
            FROM 
                usuarios 
            JOIN 
                perfil
            ON
                usuarios.rela_perfil = perfil.id_perfil
            JOIN
                contacto
            ON 
                usuarios.rela_contacto = contacto.id_contacto
            JOIN
                persona
            ON
                persona.id_persona = contacto.rela_persona
            WHERE 
                usuarios.username LIKE '{$username}'";

        $datos = $conexion->query($sql);

        while ($reg = $datos->fetch_assoc()) {
        	$id_usuario = $reg['id_usuario'];
            $usuario    = $reg['username'];
            $id_email   = $reg['id_contacto'];
            $email      = $reg['descripcion_contacto'];
            $id_perfil  = $reg['id_perfil'];
            $perfil     = $reg['descripcion_perfil'];
            $id_persona = $reg['id_persona'];
        }
        if ($perfil == "propietario" || $perfil == "administrador") {
            $sql = "SELECT rela_complejo FROM asignacion_persona_complejo WHERE rela_persona = $id_persona";
            $registros = $conexion->query($sql);
            if($registros->num_rows >= 1){
                $complejos = [];
                foreach ($registros as $reg) {
                    $complejos[] = $reg['rela_complejo'];
                }
            }
        }

        if ($perfil == "empleado" || $perfil == "administrador") {
            $sql = "SELECT rela_sucursal FROM empleado WHERE rela_persona = $id_persona";
            $registros = $conexion->query($sql);
            if($registros->num_rows >= 1){ 
                $sucursales = [];
                foreach ($registros as $reg) {
                    $sucursales[] = $reg['rela_sucursal'];
                }
            }
        }

        session_start();

        $_SESSION['id_usuario']                             =   $id_usuario;
        $_SESSION['usuario']                                =   $usuario;
        $_SESSION['id_email']                               =   $id_email;
        $_SESSION['email']                                  =   $email;
        $_SESSION['id_perfil']                              =   $id_perfil;
        $_SESSION['perfil']                                 =   $perfil;
        $_SESSION['id_persona']                             =   $id_persona;
        if (isset($complejos)) {$_SESSION['complejos']      =   $complejos;}
        if (isset($sucursales)) {$_SESSION['sucursales']    =   $sucursales;}

        header('location: ../index2.php');
        exit();
    }
    else {
    //        echo"no existe";
    header('location: inicio_sesion.php?error=2');
    }

} else {
    $sql="SELECT 
            usuarios.id_usuario,
            usuarios.username,
            contacto.descripcion_contacto,
            contacto.id_contacto
        FROM 
            usuarios
        JOIN
            contacto
        ON
            usuarios.rela_contacto = contacto.id_contacto
        WHERE 
            usuarios.username LIKE '{$username}'
        AND
            usuarios.estado LIKE 'no verificado'";

    $datos = $conexion->query($sql);
    if ($datos->num_rows == 1) {
        $reg = $datos->fetch_assoc();
        $email = $reg['descripcion_contacto'];

        header("location: inicio_sesion.php?verificacion_expirada&email={$email}&username={$username}");

    } else{

        // echo"usuario inexistente";
        header('location: inicio_sesion.php?error=1');
    }
    
}
?>