<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'conexion.php';
require_once 'registrar_bitacora.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id       = intval($_POST['id'] ?? 0);
    $pagina   = intval($_POST['pagina'] ?? 1); // Capturamos la sección actual
    $nombre   = trim($_POST['nombre'] ?? '');
    $cedula   = trim($_POST['cedula'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo   = trim($_POST['correo'] ?? '');
    $tipo     = trim($_POST['tipo'] ?? '');

    if (empty($id) || empty($nombre) || empty($cedula) || empty($telefono) || empty($correo) || empty($tipo)) {
        header("Location: ../vista/editar_usuario.php?id={$id}&pagina={$pagina}&error=campos_vacios");
        exit();
    }

    // 1. Validar Cédula duplicada en otro usuario
    $check_cedula = $conexion->prepare("SELECT id FROM usuarios WHERE cedula = ? AND id != ?");
    $check_cedula->bind_param("si", $cedula, $id);
    $check_cedula->execute();
    if ($check_cedula->get_result()->num_rows > 0) {
        $check_cedula->close();
        $conexion->close();
        header("Location: ../vista/editar_usuario.php?id={$id}&pagina={$pagina}&error=duplicado&campo=cedula");
        exit();
    }
    $check_cedula->close();

    // 2. Validar Teléfono duplicado en otro usuario
    $check_telefono = $conexion->prepare("SELECT id FROM usuarios WHERE telefono = ? AND id != ?");
    $check_telefono->bind_param("si", $telefono, $id);
    $check_telefono->execute();
    if ($check_telefono->get_result()->num_rows > 0) {
        $check_telefono->close();
        $conexion->close();
        header("Location: ../vista/editar_usuario.php?id={$id}&pagina={$pagina}&error=duplicado&campo=telefono");
        exit();
    }
    $check_telefono->close();

    // 3. Validar Correo duplicado en otro usuario
    $check_correo = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ? AND id != ?");
    $check_correo->bind_param("si", $correo, $id);
    $check_correo->execute();
    if ($check_correo->get_result()->num_rows > 0) {
        $check_correo->close();
        $conexion->close();
        header("Location: ../vista/editar_usuario.php?id={$id}&pagina={$pagina}&error=duplicado&campo=correo");
        exit();
    }
    $check_correo->close();

    // 4. Actualizar registro si pasa todas las validaciones
    $stmt_up = $conexion->prepare("UPDATE usuarios SET nombre = ?, cedula = ?, telefono = ?, correo = ?, tipo = ? WHERE id = ?");
    $stmt_up->bind_param("sssssi", $nombre, $cedula, $telefono, $correo, $tipo, $id);
    
    if ($stmt_up->execute()) {
        $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
        $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
        $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
        $descripcion = "El {$rol} {$nombre_user} ha actualizado los datos del usuario {$nombre}{$cedula_txt} (Rol: {$tipo}).";
        registrar_bitacora($conexion, "Edición de Usuario", $descripcion);

        $stmt_up->close();
        $conexion->close();
        // Redirige de regreso a la sección exacta
        header("Location: ../vista/administrar_usuarios.php?pagina={$pagina}&edit_exito=1");
        exit();
    } else {
        $stmt_up->close();
        $conexion->close();
        header("Location: ../vista/editar_usuario.php?id={$id}&pagina={$pagina}&error=1");
        exit();
    }
} else {
    header("Location: ../vista/administrar_usuarios.php");
    exit();
}
?>