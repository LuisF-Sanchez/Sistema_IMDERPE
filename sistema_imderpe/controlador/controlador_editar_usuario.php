<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'conexion.php';
require_once 'registrar_bitacora.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id       = intval($_POST['id']);
    $nombre   = trim($_POST['nombre']);
    $cedula   = trim($_POST['cedula']);
    $telefono = trim($_POST['telefono']);
    $correo   = trim($_POST['correo']);
    $tipo     = trim($_POST['tipo']);

    if (empty($id) || empty($nombre) || empty($cedula) || empty($tipo)) {
        header("Location: ../vista/administrar_usuarios.php?error=campos_vacios");
        exit();
    }

    $stmt_check = $conexion->prepare("SELECT id FROM usuarios WHERE cedula = ? AND id != ?");
    $stmt_check->bind_param("si", $cedula, $id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $stmt_check->close();
        header("Location: ../vista/administrar_usuarios.php?error_duplicado=1");
        exit();
    }
    $stmt_check->close();

    $stmt_up = $conexion->prepare("UPDATE usuarios SET nombre = ?, cedula = ?, telefono = ?, correo = ?, tipo = ? WHERE id = ?");
    $stmt_up->bind_param("sssssi", $nombre, $cedula, $telefono, $correo, $tipo, $id);
    
    if ($stmt_up->execute()) {
        $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
        $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
        $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
        $descripcion = "El {$rol} {$nombre_user} ha actualizado los datos del usuario {$nombre}{$cedula_txt} (Rol: {$tipo}).";
        registrar_bitacora($conexion, "Edición de Usuario", $descripcion);

        $stmt_up->close();
        header("Location: ../vista/administrar_usuarios.php?edit_exito=1");
    } else {
        $stmt_up->close();
        header("Location: ../vista/administrar_usuarios.php?error=1");
    }
} else {
    header("Location: ../vista/administrar_usuarios.php");
}
$conexion->close();
?>