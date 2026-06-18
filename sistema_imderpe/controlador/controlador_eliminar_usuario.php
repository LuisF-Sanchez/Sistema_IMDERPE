<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}

require_once 'conexion.php';

if (isset($_GET['id']) && isset($_GET['tipo'])) {
    $id = intval($_GET['id']);
    $tipo = $_GET['tipo'];

    $tabla = ($tipo === 'administrador') ? 'administrador' : 'usuarios';

    $sql = "DELETE FROM $tabla WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
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