<?php

require_once '../controlador/conexion.php'; 

$sql = "SELECT id, cedula, nombre, apellido, telefono, correo, estado FROM empleados";
$resultado = $conexion->query($sql);

?>