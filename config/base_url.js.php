<?php
header("Content-Type: application/javascript");
require_once("config/root_path.php");
echo "const base_url = '" . BASE_URL . "';";
?>