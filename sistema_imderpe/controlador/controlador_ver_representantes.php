<?php
require_once '../controlador/conexion.php';

$sql = "SELECT id, cedula, nombre, apellido, telefono, correo, direccion FROM representantes";
$resultado = $conexion->query($sql);
?>