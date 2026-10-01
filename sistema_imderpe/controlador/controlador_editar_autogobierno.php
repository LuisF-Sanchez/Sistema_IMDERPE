<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'conexion.php';
require_once 'registrar_bitacora.php';

if (isset($_POST['btn_editar'])) {
    
    $id        = intval($_POST['id'] ?? 0);
    $nombre    = trim($_POST['nombre'] ?? '');
    $apellido  = trim($_POST['apellido'] ?? '');
    $cedula    = trim($_POST['cedula'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $correo    = trim($_POST['correo'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $comuna    = trim($_POST['comuna'] ?? '');

    if ($id > 0 && !empty($nombre) && !empty($apellido) && !empty($cedula) && !empty($telefono) && !empty($correo) && !empty($direccion) && !empty($comuna)) {
        
        // 1. Validar cédula duplicada
        $check_cedula = $conexion->prepare("SELECT id FROM autogobierno WHERE cedula = ? AND id != ?");
        $check_cedula->bind_param("si", $cedula, $id);
        $check_cedula->execute();
        $res_cedula = $check_cedula->get_result();
        
        if ($res_cedula->num_rows > 0) {
            $check_cedula->close();
            $conexion->close();
            header("Location: ../vista/editar_autogobierno.php?id={$id}&error=duplicado&campo=cedula");
            exit();
        }
        $check_cedula->close();

        // 2. Validar teléfono duplicado
        $check_telefono = $conexion->prepare("SELECT id FROM autogobierno WHERE telefono = ? AND id != ?");
        $check_telefono->bind_param("si", $telefono, $id);
        $check_telefono->execute();
        $res_telefono = $check_telefono->get_result();

        if ($res_telefono->num_rows > 0) {
            $check_telefono->close();
            $conexion->close();
            header("Location: ../vista/editar_autogobierno.php?id={$id}&error=duplicado&campo=telefono");
            exit();
        }
        $check_telefono->close();

        // 3. Validar correo duplicado
        $check_correo = $conexion->prepare("SELECT id FROM autogobierno WHERE correo = ? AND id != ?");
        $check_correo->bind_param("si", $correo, $id);
        $check_correo->execute();
        $res_correo = $check_correo->get_result();

        if ($res_correo->num_rows > 0) {
            $check_correo->close();
            $conexion->close();
            header("Location: ../vista/editar_autogobierno.php?id={$id}&error=duplicado&campo=correo");
            exit();
        }
        $check_correo->close();

        // 4. Actualizar información
        $sql = "UPDATE autogobierno 
                SET nombre = ?, apellido = ?, cedula = ?, telefono = ?, correo = ?, direccion = ?, comuna = ? 
                WHERE id = ?";
                
        $stmt_update = $conexion->prepare($sql);
        $stmt_update->bind_param("sssssssi", $nombre, $apellido, $cedula, $telefono, $correo, $direccion, $comuna, $id);

        if ($stmt_update->execute()) {
            $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
            $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
            $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
            $descripcion = "El {$rol} {$nombre_user} ha actualizado los datos del responsable de autogobierno {$nombre} {$apellido}{$cedula_txt}.";
            registrar_bitacora($conexion, "Edición de Autogobierno", $descripcion);

            $stmt_update->close();
            $conexion->close();
            header("Location: ../vista/ver_autogobierno.php?edit_exito=1");
            exit();
        } else {
            $stmt_update->close();
            $conexion->close();
            header("Location: ../vista/editar_autogobierno.php?id={$id}&error_db=1");
            exit();
        }

    } else {
        $conexion->close();
        header("Location: ../vista/editar_autogobierno.php?id={$id}&error_campos=1");
        exit();
    }
} else {
    header("Location: ../vista/ver_autogobierno.php");
    exit();
}
?>