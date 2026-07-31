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

if (isset($_GET['id']) && isset($_GET['tipo'])) {
    $id = intval($_GET['id']);
    $tipo = $_GET['tipo'];

    $usuario_info = "ID: {$id}";
    $stmt_get = $conexion->prepare("SELECT nombre, cedula FROM usuarios WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    if ($stmt_get->execute()) {
        $res = $stmt_get->get_result();
        if ($row = $res->fetch_assoc()) {
            $cedula_txt = !empty($row['cedula']) ? " (C.I. {$row['cedula']})" : "";
            $usuario_info = "{$row['nombre']}{$cedula_txt}";
        }
    }
    $stmt_get->close();

    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
        $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
        $descripcion = "El {$rol} {$nombre_user} ha eliminado al usuario del sistema {$usuario_info}.";
        registrar_bitacora($conexion, "Eliminación de Usuario", $descripcion);

        $stmt->close();
        header("Location: ../vista/administrar_usuarios.php?eliminar_exito=ok");
        exit();
    } else {
        $stmt->close();
        header("Location: ../vista/administrar_usuarios.php?error_eliminar=ok");
        exit();
    }
} else {
    header("Location: ../vista/administrar_usuarios.php");
    exit();
}
?>