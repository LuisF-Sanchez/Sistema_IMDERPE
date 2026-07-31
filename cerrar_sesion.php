<?php
session_start();
require_once 'conexion.php';
require_once 'registrar_bitacora.php';

if (isset($_SESSION['usuario_nombre'])) {
    $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
    $nombre = $_SESSION['usuario_nombre'];
    $descripcion = "El {$rol} {$nombre} ha cerrado sesión en el sistema.";
    registrar_bitacora($conexion, "Cierre de Sesión", $descripcion);
}

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

header("Location: ../vista/index.php");
exit();
?>