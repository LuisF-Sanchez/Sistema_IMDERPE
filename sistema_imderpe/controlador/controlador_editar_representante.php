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
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $direccion = trim($_POST['direccion']);

    // 1. Validar CÉDULA duplicada
    $check_cedula = $conexion->prepare("SELECT id FROM representantes WHERE cedula = ? AND id <> ?");
    $check_cedula->bind_param("si", $cedula, $id);
    $check_cedula->execute();
    if ($check_cedula->get_result()->num_rows > 0) {
        $check_cedula->close();
        header("Location: ../vista/editar_representante.php?id={$id}&error=duplicado&campo=cedula");
        exit();
    }
    $check_cedula->close();

    // 2. Validar TELÉFONO duplicado
    if (!empty($telefono)) {
        $check_telefono = $conexion->prepare("SELECT id FROM representantes WHERE telefono = ? AND id <> ?");
        $check_telefono->bind_param("si", $telefono, $id);
        $check_telefono->execute();
        if ($check_telefono->get_result()->num_rows > 0) {
            $check_telefono->close();
            header("Location: ../vista/editar_representante.php?id={$id}&error=duplicado&campo=telefono");
            exit();
        }
        $check_telefono->close();
    }

    // 3. Validar CORREO duplicado
    if (!empty($correo)) {
        $check_correo = $conexion->prepare("SELECT id FROM representantes WHERE correo = ? AND id <> ?");
        $check_correo->bind_param("si", $correo, $id);
        $check_correo->execute();
        if ($check_correo->get_result()->num_rows > 0) {
            $check_correo->close();
            header("Location: ../vista/editar_representante.php?id={$id}&error=duplicado&campo=correo");
            exit();
        }
        $check_correo->close();
    }

    // Actualizar datos del representante
    $update_sql = "UPDATE representantes SET cedula = ?, nombre = ?, apellido = ?, telefono = ?, correo = ?, direccion = ? WHERE id = ?";
    $stmt_update = $conexion->prepare($update_sql);
    $stmt_update->bind_param("ssssssi", $cedula, $nombre, $apellido, $telefono, $correo, $direccion, $id);

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