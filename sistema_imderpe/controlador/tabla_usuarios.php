<?php
$conexion = new mysqli("localhost", "root", "", "imderpe");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "
    SELECT id, nombre, cedula, telefono, correo, tipo 
    FROM administrador
    UNION
    SELECT id, nombre, cedula, telefono, correo, tipo 
    FROM usuarios
    ORDER BY tipo ASC, nombre ASC
";

$resultado = $conexion->query($sql);
?>