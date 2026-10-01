<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'conexion.php';
require_once 'registrar_bitacora.php';

if (isset($_POST['btn_registrar'])) {
    
    $nombre    = trim($_POST['nombre'] ?? '');
    $apellido  = trim($_POST['apellido'] ?? '');
    $cedula    = trim($_POST['cedula'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $correo    = trim($_POST['correo'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $comuna    = trim($_POST['comuna'] ?? '');

    if (!empty($nombre) && !empty($apellido) && !empty($cedula) && !empty($telefono) && !empty($correo) && !empty($direccion) && !empty($comuna)) {
        
        // 1. Validar cédula duplicada
        $check_cedula = $conexion->prepare("SELECT id FROM autogobierno WHERE cedula = ?");
        $check_cedula->bind_param("s", $cedula);
        $check_cedula->execute();
        $res_cedula = $check_cedula->get_result();
        
        if ($res_cedula->num_rows > 0) {
            $check_cedula->close();
            $conexion->close();
            header("Location: ../vista/registrar_autogobierno.php?error=duplicado&campo=cedula");
            exit();
        }
        $check_cedula->close();

        // 2. Validar teléfono duplicado
        $check_telefono = $conexion->prepare("SELECT id FROM autogobierno WHERE telefono = ?");
        $check_telefono->bind_param("s", $telefono);
        $check_telefono->execute();
        $res_telefono = $check_telefono->get_result();

        if ($res_telefono->num_rows > 0) {
            $check_telefono->close();
            $conexion->close();
            header("Location: ../vista/registrar_autogobierno.php?error=duplicado&campo=telefono");
            exit();
        }
        $check_telefono->close();

        // 3. Validar correo duplicado
        $check_correo = $conexion->prepare("SELECT id FROM autogobierno WHERE correo = ?");
        $check_correo->bind_param("s", $correo);
        $check_correo->execute();
        $res_correo = $check_correo->get_result();

        if ($res_correo->num_rows > 0) {
            $check_correo->close();
            $conexion->close();
            header("Location: ../vista/registrar_autogobierno.php?error=duplicado&campo=correo");
            exit();
        }
        $check_correo->close();

        // 4. Insertar nuevo registro
        $sql = "INSERT INTO autogobierno (nombre, apellido, cedula, telefono, correo, direccion, comuna) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conexion->prepare($sql);
        $stmt_insert->bind_param("sssssss", $nombre, $apellido, $cedula, $telefono, $correo, $direccion, $comuna);

        if ($stmt_insert->execute()) {
            $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
            $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
            $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
            $descripcion = "El {$rol} {$nombre_user} ha registrado al responsable de autogobierno {$nombre} {$apellido}{$cedula_txt}.";
            registrar_bitacora($conexion, "Registro de Autogobierno", $descripcion);

            $stmt_insert->close();
            $conexion->close();
            header("Location: ../vista/ver_autogobierno.php?registro=exito");
            exit();
        } else {
            $stmt_insert->close();
            $conexion->close();
            header("Location: ../vista/registrar_autogobierno.php?error_db=1");
            exit();
        }

    } else {
        $conexion->close();
        header("Location: ../vista/registrar_autogobierno.php?error_campos=1");
        exit();
    }
} else {
    header("Location: ../vista/ver_autogobierno.php");
    exit();
}
?>