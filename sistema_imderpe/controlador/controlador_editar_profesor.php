<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $cedula = trim($_POST['cedula']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $instituto_educativo = trim($_POST['instituto_educativo']);

    if (empty($id) || empty($cedula) || empty($nombre) || empty($apellido) || empty($instituto_educativo)) {
        header("Location: ../vista/ver_profesores.php?error=campos_vacios");
        exit();
    }

    $stmt_check = $conexion->prepare("SELECT id FROM profesor_edu WHERE cedula = ? AND id != ?");
    $stmt_check->bind_param("si", $cedula, $id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $stmt_check->close();
        header("Location: ../vista/ver_profesores.php?error_duplicado=1");
        exit();
    }
    $stmt_check->close();

    $stmt = $conexion->prepare("UPDATE profesor_edu SET nombre = ?, apellido = ?, cedula = ?, telefono = ?, correo = ?, instituto_educativo = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $nombre, $apellido, $cedula, $telefono, $correo, $instituto_educativo, $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: ../vista/ver_profesores.php?edit_exito=1");
    } else {
        $stmt->close();
        header("Location: ../vista/ver_profesores.php?error=1");
    }
} else {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $stmt_get = $conexion->prepare("SELECT * FROM profesor_edu WHERE id = ?");
        $stmt_get->bind_param("i", $id);
        $stmt_get->execute();
        $resultado_profesor = $stmt_get->get_result();
        
        if ($resultado_profesor->num_rows > 0) {
            $profesor = $resultado_profesor->fetch_assoc();
        } else {
            header("Location: ../vista/ver_profesores.php");
            exit();
        }
        $stmt_get->close();
    } else {
        header("Location: ../vista/ver_profesores.php");
        exit();
    }
}