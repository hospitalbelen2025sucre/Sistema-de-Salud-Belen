<?php
session_start();

// Destruir todas las variables de sesi�n
$_SESSION = array();

// Destruir la sesi�n
session_destroy();

// Redirigir a la p�gina principal
header('Location: index.php');
exit();
?>
