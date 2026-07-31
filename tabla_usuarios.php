<?php
require_once 'conexion.php';

$sql = "SELECT id, nombre, cedula, telefono, correo, tipo 
        FROM usuarios 
        ORDER BY tipo ASC, nombre ASC";

$resultado = $conexion->query($sql);
?>