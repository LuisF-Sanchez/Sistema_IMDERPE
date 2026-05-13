<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $estado = $_POST['estado'];


    $stmt = $conexion->prepare("UPDATE empleados SET cedula=?, nombre=?, apellido=?, telefono=?, correo=?, estado=? WHERE id=?");
    $stmt->bind_param("ssssssi", $cedula, $nombre, $apellido, $telefono, $correo, $estado, $id);

    if ($stmt->execute()) {

        header("Location: ../vista/ver_empleados.php?edit_exito=true");
    } else {
        echo "Error al actualizar: " . $conexion->error;
    }

    $stmt->close();
}
$conexion->close();
?>