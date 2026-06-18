<?php

require_once '../controlador/conexion.php';

$cedula   = $_POST['cedula'];
$nombre   = $_POST['nombre'];
$apellido = $_POST['apellido'];
$cargo    = $_POST['cargo'];
$telefono = $_POST['telefono'];
$correo   = $_POST['correo'];
$estado   = $_POST['estado'];

$sql = "INSERT INTO empleados (cedula, nombre, apellido, cargo, telefono, correo, estado) 
        VALUES ('$cedula', '$nombre', '$apellido', '$cargo', '$telefono', '$correo', '$estado')";

if ($conexion->query($sql) === TRUE) {
    header("Location: ../vista/ver_empleados.php?registro=exito");
    exit(); 
} else {
    echo "Error: " . $sql . "<br>" . $conexion->error;
}

$conexion->close();
?>