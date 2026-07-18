<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = trim($_POST['cedula']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $instituto_educativo = trim($_POST['instituto_educativo']);

    if (empty($cedula) || empty($nombre) || empty($apellido) || empty($instituto_educativo)) {
        header("Location: ../vista/ver_profesores.php?error=campos_vacios");
        exit();
    }

    $stmt_check = $conexion->prepare("SELECT id FROM profesor_edu WHERE cedula = ?");
    $stmt_check->bind_param("s", $cedula);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $stmt_check->close();
        header("Location: ../vista/ver_profesores.php?error_duplicado=1");
        exit();
    }
    $stmt_check->close();

    $stmt = $conexion->prepare("INSERT INTO profesor_edu (nombre, apellido, cedula, telefono, correo, instituto_educativo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nombre, $apellido, $cedula, $telefono, $correo, $instituto_educativo);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: ../vista/ver_profesores.php?registro=exito");
    } else {
        $stmt->close();
        header("Location: ../vista/ver_profesores.php?error=1");
    }
} else {
    header("Location: ../vista/ver_profesores.php");
}
$conexion->close();