<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'conexion.php';

$query = "SELECT id, nombre, apellido, cedula, telefono, correo, direccion, comuna 
          FROM autogobierno 
          ORDER BY nombre ASC, apellido ASC";

$resultado = $conexion->query($query);
?>