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

// Capturamos la sección actual (por defecto la 1 si no viene definida)
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt_get = $conexion->prepare("SELECT nombre, cedula, tipo FROM usuarios WHERE id = ?");
    $stmt_get->bind_param("i", $id);
    $stmt_get->execute();
    $res = $stmt_get->get_result();
    
    if ($row = $res->fetch_assoc()) {
        $nombre_a_borrar = $row['nombre'];
        $usuario_info = "{$row['nombre']}" . (!empty($row['cedula']) ? " (C.I. {$row['cedula']})" : "");
    } else {
        $stmt_get->close();
        header("Location: ../vista/administrar_usuarios.php?pagina={$pagina}&error=no_encontrado");
        exit();
    }
    $stmt_get->close();

    $current_user_name = $_SESSION['usuario_nombre'] ?? '';
    $current_user_id = $_SESSION['usuario_id'] ?? 0;

    if ($id === $current_user_id || strcasecmp(trim($nombre_a_borrar), trim($current_user_name)) === 0) {
        header("Location: ../vista/administrar_usuarios.php?pagina={$pagina}&error=auto_eliminacion");
        exit();
    }

    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Administrador');
        $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
        $descripcion = "El {$rol} {$nombre_user} ha eliminado al usuario del sistema {$usuario_info}.";
        
        if (function_exists('registrar_bitacora')) {
            registrar_bitacora($conexion, "Eliminación de Usuario", $descripcion);
        }

        $stmt->close();

        header("Location: ../vista/administrar_usuarios.php?pagina={$pagina}&eliminar_exito=ok");
        exit();
    } else {
        $stmt->close();
        header("Location: ../vista/administrar_usuarios.php?pagina={$pagina}&error_eliminar=ok");
        exit();
    }
} else {
    header("Location: ../vista/administrar_usuarios.php?pagina={$pagina}");
    exit();
}
?>