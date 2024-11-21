<?php
$conexion = new mysqli('localhost', 'root', '1998supre', 'poemas_db');

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}
?>
