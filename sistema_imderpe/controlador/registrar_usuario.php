<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'conexion.php';
require_once 'registrar_bitacora.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre     = trim($_POST['nombre']);
    $cedula     = trim($_POST['cedula']);
    $telefono   = trim($_POST['telefono']);
    $correo     = trim($_POST['correo']);
    $contraseña = $_POST['contraseña']; 
    $tipo       = trim($_POST['tipo']);

    if (empty($nombre) || empty($cedula) || empty($contraseña) || empty($tipo)) {
        header("Location: ../vista/registrar.php?error=campos_vacios");
        exit();
    }

    $stmt_check = $conexion->prepare("SELECT id FROM usuarios WHERE cedula = ?");
    $stmt_check->bind_param("s", $cedula);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $stmt_check->close();
        header("Location: ../vista/administrar_usuarios.php?error_duplicado=1");
        exit();
    }
    $stmt_check->close();

    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, cedula, telefono, correo, contraseña, tipo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nombre, $cedula, $telefono, $correo, $contraseña, $tipo);

    if ($stmt->execute()) {
        $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
        $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
        $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
        $descripcion = "El {$rol} {$nombre_user} ha registrado un nuevo usuario del sistema: {$nombre}{$cedula_txt} (Rol: {$tipo}).";
        registrar_bitacora($conexion, "Registro de Usuario", $descripcion);

        $stmt->close();
        header("Location: ../vista/administrar_usuarios.php?success=true");
        exit();
    } else {
        $stmt->close();
        header("Location: ../vista/administrar_usuarios.php?error=1");
        exit();
    }
}

$conexion->close();
?>