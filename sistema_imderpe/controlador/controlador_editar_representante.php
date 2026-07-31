<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}

require_once 'conexion.php';
require_once 'registrar_bitacora.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = intval($_POST['id']);
    $cedula = trim($_POST['cedula']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $correo = trim($_POST['correo']);
    $direccion = trim($_POST['direccion']);

    $check_sql = "SELECT id FROM representantes WHERE (cedula = ? OR correo = ?) AND id <> ?";
    $stmt_check = $conexion->prepare($check_sql);
    $stmt_check->bind_param("ssi", $cedula, $correo, $id);
    $stmt_check->execute();
    $res_check = $stmt_check->get_result();

    if ($res_check->num_rows > 0) {
        $stmt_check->close();
        header("Location: ../vista/ver_representantes.php?error_duplicado=ok");
        exit();
    }
    $stmt_check->close();

    $update_sql = "UPDATE representantes SET cedula = ?, nombre = ?, apellido = ?, correo = ?, direccion = ? WHERE id = ?";
    $stmt_update = $conexion->prepare($update_sql);
    
    $stmt_update->bind_param("sssssi", $cedula, $nombre, $apellido, $correo, $direccion, $id);

    if ($stmt_update->execute()) {
        $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
        $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
        $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
        $descripcion = "El {$rol} {$nombre_user} ha actualizado los datos del representante {$nombre} {$apellido}{$cedula_txt}.";
        registrar_bitacora($conexion, "Edición de Representante", $descripcion);

        $stmt_update->close();
        header("Location: ../vista/ver_representantes.php?edit_exito=ok");
        exit();
    } else {
        $stmt_update->close();
        header("Location: ../vista/ver_representantes.php?error_general=ok");
        exit();
    }
} else {
    header("Location: ../vista/ver_representantes.php");
    exit();
}
?>