<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'conexion.php';
require_once 'registrar_bitacora.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula              = trim($_POST['cedula'] ?? '');
    $nombre              = trim($_POST['nombre'] ?? '');
    $apellido            = trim($_POST['apellido'] ?? '');
    $telefono            = trim($_POST['telefono'] ?? '');
    $correo              = trim($_POST['correo'] ?? '');
    $instituto_educativo = trim($_POST['instituto_educativo'] ?? '');

    if (!empty($cedula) && !empty($nombre) && !empty($apellido) && !empty($telefono) && !empty($correo) && !empty($instituto_educativo)) {
        
        // 1. Validar Cédula duplicada
        $check_cedula = $conexion->prepare("SELECT id FROM profesor_edu WHERE cedula = ?");
        $check_cedula->bind_param("s", $cedula);
        $check_cedula->execute();
        $res_cedula = $check_cedula->get_result();

        if ($res_cedula->num_rows > 0) {
            $check_cedula->close();
            $conexion->close();
            header("Location: ../vista/registrar_profesor.php?error=duplicado&campo=cedula");
            exit();
        }
        $check_cedula->close();

        // 2. Validar Teléfono duplicado
        $check_telefono = $conexion->prepare("SELECT id FROM profesor_edu WHERE telefono = ?");
        $check_telefono->bind_param("s", $telefono);
        $check_telefono->execute();
        $res_telefono = $check_telefono->get_result();

        if ($res_telefono->num_rows > 0) {
            $check_telefono->close();
            $conexion->close();
            header("Location: ../vista/registrar_profesor.php?error=duplicado&campo=telefono");
            exit();
        }
        $check_telefono->close();

        // 3. Validar Correo duplicado
        $check_correo = $conexion->prepare("SELECT id FROM profesor_edu WHERE correo = ?");
        $check_correo->bind_param("s", $correo);
        $check_correo->execute();
        $res_correo = $check_correo->get_result();

        if ($res_correo->num_rows > 0) {
            $check_correo->close();
            $conexion->close();
            header("Location: ../vista/registrar_profesor.php?error=duplicado&campo=correo");
            exit();
        }
        $check_correo->close();

        // 4. Insertar si pasa todas las comprobaciones
        $stmt = $conexion->prepare("INSERT INTO profesor_edu (nombre, apellido, cedula, telefono, correo, instituto_educativo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $nombre, $apellido, $cedula, $telefono, $correo, $instituto_educativo);

        if ($stmt->execute()) {
            $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
            $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
            $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
            $descripcion = "El {$rol} {$nombre_user} ha registrado al profesor de educación física {$nombre} {$apellido}{$cedula_txt}.";
            registrar_bitacora($conexion, "Registro de Profesor", $descripcion);

            $stmt->close();
            $conexion->close();
            header("Location: ../vista/ver_profesores.php?registro=exito");
            exit();
        } else {
            $stmt->close();
            $conexion->close();
            header("Location: ../vista/registrar_profesor.php?error=1");
            exit();
        }

    } else {
        $conexion->close();
        header("Location: ../vista/registrar_profesor.php?error=campos_vacios");
        exit();
    }
} else {
    header("Location: ../vista/ver_profesores.php");
    exit();
}
?>