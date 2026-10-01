<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'conexion.php';
require_once 'registrar_bitacora.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id                  = intval($_POST['id'] ?? 0);
    $cedula              = trim($_POST['cedula'] ?? '');
    $nombre              = trim($_POST['nombre'] ?? '');
    $apellido            = trim($_POST['apellido'] ?? '');
    $telefono            = trim($_POST['telefono'] ?? '');
    $correo              = trim($_POST['correo'] ?? '');
    $instituto_educativo = trim($_POST['instituto_educativo'] ?? '');

    if (empty($id) || empty($cedula) || empty($nombre) || empty($apellido) || empty($telefono) || empty($correo) || empty($instituto_educativo)) {
        header("Location: ../vista/editar_profesor.php?id={$id}&error=campos_vacios");
        exit();
    }

    // 1. Validar si la Cédula pertenece a otro registro
    $check_cedula = $conexion->prepare("SELECT id FROM profesor_edu WHERE cedula = ? AND id != ?");
    $check_cedula->bind_param("si", $cedula, $id);
    $check_cedula->execute();
    if ($check_cedula->get_result()->num_rows > 0) {
        $check_cedula->close();
        $conexion->close();
        header("Location: ../vista/editar_profesor.php?id={$id}&error=duplicado&campo=cedula");
        exit();
    }
    $check_cedula->close();

    // 2. Validar si el Teléfono pertenece a otro registro
    $check_telefono = $conexion->prepare("SELECT id FROM profesor_edu WHERE telefono = ? AND id != ?");
    $check_telefono->bind_param("si", $telefono, $id);
    $check_telefono->execute();
    if ($check_telefono->get_result()->num_rows > 0) {
        $check_telefono->close();
        $conexion->close();
        header("Location: ../vista/editar_profesor.php?id={$id}&error=duplicado&campo=telefono");
        exit();
    }
    $check_telefono->close();

    // 3. Validar si el Correo pertenece a otro registro
    $check_correo = $conexion->prepare("SELECT id FROM profesor_edu WHERE correo = ? AND id != ?");
    $check_correo->bind_param("si", $correo, $id);
    $check_correo->execute();
    if ($check_correo->get_result()->num_rows > 0) {
        $check_correo->close();
        $conexion->close();
        header("Location: ../vista/editar_profesor.php?id={$id}&error=duplicado&campo=correo");
        exit();
    }
    $check_correo->close();

    // 4. Proceder con el UPDATE si no hay duplicaciones
    $stmt = $conexion->prepare("UPDATE profesor_edu SET nombre = ?, apellido = ?, cedula = ?, telefono = ?, correo = ?, instituto_educativo = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $nombre, $apellido, $cedula, $telefono, $correo, $instituto_educativo, $id);

    if ($stmt->execute()) {
        $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
        $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
        $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
        $descripcion = "El {$rol} {$nombre_user} ha actualizado los datos del profesor de educación física {$nombre} {$apellido}{$cedula_txt}.";
        registrar_bitacora($conexion, "Edición de Profesor", $descripcion);

        $stmt->close();
        $conexion->close();
        header("Location: ../vista/ver_profesores.php?edit_exito=1");
        exit();
    } else {
        $stmt->close();
        $conexion->close();
        header("Location: ../vista/editar_profesor.php?id={$id}&error=1");
        exit();
    }
} else {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $stmt_get = $conexion->prepare("SELECT * FROM profesor_edu WHERE id = ?");
        $stmt_get->bind_param("i", $id);
        $stmt_get->execute();
        $resultado_profesor = $stmt_get->get_result();
        
        if ($resultado_profesor->num_rows > 0) {
            $profesor = $resultado_profesor->fetch_assoc();
        } else {
            header("Location: ../vista/ver_profesores.php");
            exit();
        }
        $stmt_get->close();
    } else {
        header("Location: ../vista/ver_profesores.php");
        exit();
    }
}
?>