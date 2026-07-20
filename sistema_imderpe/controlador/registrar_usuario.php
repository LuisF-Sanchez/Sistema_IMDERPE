<?php
require_once 'conexion.php';

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