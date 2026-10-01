<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'conexion.php';
require_once 'registrar_bitacora.php';

if (!empty($_POST["btn_editar"])) {

    $id = intval($_POST["id"] ?? 0);
    $cedula = trim($_POST["cedula"] ?? '');
    $nombre = trim($_POST["nombre"] ?? '');
    $apellido = trim($_POST["apellido"] ?? '');
    $disciplina_id = trim($_POST["disciplina_id"] ?? '');
    $telefono = trim($_POST["telefono"] ?? '');
    $correo = trim($_POST["correo"] ?? '');
    $estado = trim($_POST["estado"] ?? 'activo');

    if (!empty($id) && !empty($cedula) && !empty($nombre) && !empty($apellido) && !empty($disciplina_id) && !empty($telefono) && !empty($correo)) {
        
        // 1. Verificar Cédula duplicada
        $check_cedula = $conexion->prepare("SELECT id FROM entrenadores WHERE cedula = ? AND id <> ?");
        $check_cedula->bind_param("si", $cedula, $id);
        $check_cedula->execute();
        $res_cedula = $check_cedula->get_result();

        if ($res_cedula->num_rows > 0) {
            $check_cedula->close();
            header("Location: ../vista/editar_entrenador.php?id={$id}&error=duplicado&campo=cedula");
            exit();
        }
        $check_cedula->close();

        // 2. Verificar Teléfono duplicado
        $check_telefono = $conexion->prepare("SELECT id FROM entrenadores WHERE telefono = ? AND id <> ?");
        $check_telefono->bind_param("si", $telefono, $id);
        $check_telefono->execute();
        $res_telefono = $check_telefono->get_result();

        if ($res_telefono->num_rows > 0) {
            $check_telefono->close();
            header("Location: ../vista/editar_entrenador.php?id={$id}&error=duplicado&campo=telefono");
            exit();
        }
        $check_telefono->close();

        // 3. Verificar Correo duplicado
        $check_correo = $conexion->prepare("SELECT id FROM entrenadores WHERE correo = ? AND id <> ?");
        $check_correo->bind_param("si", $correo, $id);
        $check_correo->execute();
        $res_correo = $check_correo->get_result();

        if ($res_correo->num_rows > 0) {
            $check_correo->close();
            header("Location: ../vista/editar_entrenador.php?id={$id}&error=duplicado&campo=correo");
            exit();
        }
        $check_correo->close();

        // Si todas las comprobaciones pasan, actualizar
        $sql = $conexion->prepare("UPDATE entrenadores SET cedula=?, nombre=?, apellido=?, disciplina_id=?, telefono=?, correo=?, estado=? WHERE id=?");
        $sql->bind_param("sssisssi", $cedula, $nombre, $apellido, $disciplina_id, $telefono, $correo, $estado, $id);

        if ($sql->execute()) {
            $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
            $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
            $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
            $descripcion = "El {$rol} {$nombre_user} ha actualizado los datos del entrenador {$nombre} {$apellido}{$cedula_txt}.";
            registrar_bitacora($conexion, "Edición de Entrenador", $descripcion);

            $sql->close();
            header("Location: ../vista/ver_entrenadores.php?edit_exito=1");
            exit();
        } else {
            $sql->close();
            header("Location: ../vista/editar_entrenador.php?id={$id}&error=1");
            exit();
        }

    } else {
        header("Location: ../vista/editar_entrenador.php?id={$id}&error_campos=1");
        exit();
    }
}

$conexion->close();
header("Location: ../vista/ver_entrenadores.php");
exit();
?>