<?php
require_once 'conexion.php';

$resultado = $conexion->query("SELECT id, cedula, nombre, apellido, correo, direccion FROM representantes ORDER BY nombre ASC");
?>