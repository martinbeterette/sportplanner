<?php 

$ubicacionActualPath = __DIR__;
$nombreDirectorio  = 'sportplanner';

define('RUTA', $ubicacionActualPath . '/' .'../');

define('BASE_URL', 'HTTP://' . $_SERVER['HTTP_HOST'] . '/' .$nombreDirectorio. '/'); 

	/*$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    echo $_SERVER['PHP_SELF'].'<br>';

    echo $protocol.$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'<br>';

    echo $_SERVER['HTTP_HOST'].'<br>';
*/
?>
