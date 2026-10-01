<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'conexion.php';
require_once 'registrar_bitacora.php';

if (!empty($_POST["btn_registrar"])) {

    $cedula = trim($_POST["cedula"] ?? '');
    $nombre = trim($_POST["nombre"] ?? '');
    $apellido = trim($_POST["apellido"] ?? '');
    $disciplina_id = trim($_POST["disciplina_id"] ?? '');
    $telefono = trim($_POST["telefono"] ?? '');
    $correo = trim($_POST["correo"] ?? '');
    $estado = trim($_POST["estado"] ?? 'activo');

    // Comprobar que los campos requeridos no estén vacíos
    if (!empty($cedula) && !empty($nombre) && !empty($apellido) && !empty($disciplina_id)) {
        
        // Comprobar si la cédula ya existe
        $check_cedula = $conexion->prepare("SELECT id FROM entrenadores WHERE cedula = ?");
        $check_cedula->bind_param("s", $cedula);
        $check_cedula->execute();
        $res_check = $check_cedula->get_result();

        if ($res_check->num_rows > 0) {
            $check_cedula->close();
            header("Location: ../vista/registrar_entrenadores.php?error=duplicado&campo=cedula");
            exit();
        }
        $check_cedula->close();

        // Registrar el nuevo entrenador
        $sql = $conexion->prepare("INSERT INTO entrenadores (cedula, nombre, apellido, disciplina_id, telefono, correo, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $sql->bind_param("sssisss", $cedula, $nombre, $apellido, $disciplina_id, $telefono, $correo, $estado);

        if ($sql->execute()) {
            $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
            $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
            $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
            $descripcion = "El {$rol} {$nombre_user} ha registrado al entrenador {$nombre} {$apellido}{$cedula_txt}.";
            registrar_bitacora($conexion, "Registro de Entrenador", $descripcion);

            $sql->close();
            header("Location: ../vista/ver_entrenadores.php?registro_exito=1");
            exit();
        } else {
            $sql->close();
            header("Location: ../vista/registrar_entrenadores.php?error=1");
            exit();
        }
    } else {
        header("Location: ../vista/registrar_entrenadores.php?error_campos=1");
        exit();
    }
}

$conexion->close();
header("Location: ../vista/registrar_entrenadores.php");
exit();
?>