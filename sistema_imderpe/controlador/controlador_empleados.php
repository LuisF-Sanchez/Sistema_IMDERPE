<?php
require_once '../controlador/conexion.php';

$sql = "SELECT id, cedula, nombre, apellido, cargo, telefono, correo, estado, foto, fecha_ingreso FROM empleados";
$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}
?>