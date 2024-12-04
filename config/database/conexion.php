<?php
$host 		= "localhost";
$user 		= "root";
$password 	= "";
$database	= "proyecto_pp2";
$dsn = "mysql:host=$host;dbname=$database";

// mysqli
$conexion = mysqli_connect($host, $user, $password, $database);
if (!$conexion) {
	die("Error en la conexión MySQLi: " . mysqli_connect_error());
}
// Conexión PDO
try {
	$pdo = new PDO($dsn, $user, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	die("Error en la conexión PDO: " . $e->getMessage());
}
