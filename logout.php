<?php
session_start(); // Iniciar la sesión

// Destruir todas las variables de sesión
session_unset(); // Eliminar todas las variables de sesión
session_destroy(); // Destruir la sesión

// Redirigir a la página de inicio o login
header('Location: home.php');
exit();
?>
