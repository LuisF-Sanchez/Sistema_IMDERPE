<?php

$host = "localhost";
$usuario = "root";     
$contrasena = "";      
$base_de_datos = "imderpe"; 

$conexion = new mysqli($host, $usuario, $contrasena, $base_de_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>