<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'conexion.php';
require_once 'registrar_bitacora.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre     = trim($_POST['nombre'] ?? '');
    $cedula     = trim($_POST['cedula'] ?? '');
    $telefono   = trim($_POST['telefono'] ?? '');
    $correo     = trim($_POST['correo'] ?? '');
    $contraseña = $_POST['contraseña'] ?? ''; 
    $tipo       = trim($_POST['tipo'] ?? '');

    if (empty($nombre) || empty($cedula) || empty($telefono) || empty($correo) || empty($contraseña) || empty($tipo)) {
        header("Location: ../vista/registrar.php?error=campos_vacios");
        exit();
    }

    // 1. Validar Cédula duplicada
    $check_cedula = $conexion->prepare("SELECT id FROM usuarios WHERE cedula = ?");
    $check_cedula->bind_param("s", $cedula);
    $check_cedula->execute();
    if ($check_cedula->get_result()->num_rows > 0) {
        $check_cedula->close();
        $conexion->close();
        header("Location: ../vista/registrar.php?error=duplicado&campo=cedula");
        exit();
    }
    $check_cedula->close();

    // 2. Validar Teléfono duplicado
    $check_telefono = $conexion->prepare("SELECT id FROM usuarios WHERE telefono = ?");
    $check_telefono->bind_param("s", $telefono);
    $check_telefono->execute();
    if ($check_telefono->get_result()->num_rows > 0) {
        $check_telefono->close();
        $conexion->close();
        header("Location: ../vista/registrar.php?error=duplicado&campo=telefono");
        exit();
    }
    $check_telefono->close();

    // 3. Validar Correo duplicado
    $check_correo = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $check_correo->bind_param("s", $correo);
    $check_correo->execute();
    if ($check_correo->get_result()->num_rows > 0) {
        $check_correo->close();
        $conexion->close();
        header("Location: ../vista/registrar.php?error=duplicado&campo=correo");
        exit();
    }
    $check_correo->close();

    // 4. Insertar usuario si pasa todas las validaciones
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, cedula, telefono, correo, contraseña, tipo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nombre, $cedula, $telefono, $correo, $contraseña, $tipo);

    if ($stmt->execute()) {
        $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
        $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
        $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
        $descripcion = "El {$rol} {$nombre_user} ha registrado un nuevo usuario del sistema: {$nombre}{$cedula_txt} (Rol: {$tipo}).";
        registrar_bitacora($conexion, "Registro de Usuario", $descripcion);

        $stmt->close();
        $conexion->close();
        header("Location: ../vista/administrar_usuarios.php?success=true");
        exit();
    } else {
        $stmt->close();
        $conexion->close();
        header("Location: ../vista/registrar.php?error=1");
        exit();
    }
} else {
    header("Location: ../vista/administrar_usuarios.php");
    exit();
}
?>