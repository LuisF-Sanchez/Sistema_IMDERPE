<?php
require_once 'conexion.php';

$query = "SELECT id, nombre, apellido, cedula, telefono, correo, instituto_educativo FROM profesor_edu ORDER BY nombre ASC";
$resultado = $conexion->query($query);