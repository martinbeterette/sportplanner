<?php  
require_once("../../../../config/root_path.php");
require_once("../probando.php");
$_GET['id_complejo'] ?? false;



?>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="<?php echo BASE_URL . "css/header.css" ?>">
	<link rel="stylesheet" href="<?php echo BASE_URL . "css/aside.css" ?>">
</head>
<body>
	<?php include(RUTA. "includes/header.php"); ?>
	<?php include(RUTA. "includes/menu_aside.php"); ?>
	<div></div>
	<script src="<?php echo BASE_URL. "libs/jquery-3.7.1.min.js" ?>"></script>
	<script src="<?php echo BASE_URL . "js/header.js" ?>"></script>
	<script src="<?php echo BASE_URL . "js/aside.js" ?>"></script>
</body>
</html>