<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $cargo = $_POST['cargo'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $estado = $_POST['estado'];

    $check = $conexion->prepare("SELECT id FROM empleados WHERE (cedula = ? OR correo = ?) AND id != ?");
    $check->bind_param("ssi", $cedula, $correo, $id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        header("Location: ../vista/ver_empleados.php?error_duplicado=true");
        exit();
    }
    $check->close();

    $stmt = $conexion->prepare("UPDATE empleados SET cedula=?, nombre=?, apellido=?, cargo=?, telefono=?, correo=?, estado=? WHERE id=?");
    $stmt->bind_param("sssssssi", $cedula, $nombre, $apellido, $cargo, $telefono, $correo, $estado, $id);

    if ($stmt->execute()) {
        header("Location: ../vista/ver_empleados.php?edit_exito=true");
        exit();
    } else {
        echo "Error al actualizar: " . $conexion->error;
    }

    $stmt->close();
}
$conexion->close();
?>