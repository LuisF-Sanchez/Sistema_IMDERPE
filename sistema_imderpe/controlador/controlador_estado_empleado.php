<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../controlador/conexion.php';
require_once '../controlador/registrar_bitacora.php';

if (isset($_GET['id']) && isset($_GET['actual'])) {
    $id = intval($_GET['id']);
    $estado_actual = $_GET['actual'];
    
    $nuevo_estado = ($estado_actual == 'activo') ? 'inactivo' : 'activo';

    $persona_info = "ID: {$id}";
    $stmt_info = $conexion->prepare("SELECT nombre, apellido, cedula FROM empleados WHERE id = ?");
    if ($stmt_info) {
        $stmt_info->bind_param("i", $id);
        $stmt_info->execute();
        $res = $stmt_info->get_result();
        if ($row = $res->fetch_assoc()) {
            $cedula = !empty($row['cedula']) ? " (C.I. {$row['cedula']})" : "";
            $persona_info = "{$row['nombre']} {$row['apellido']}{$cedula}";
        }
        $stmt_info->close();
    }

    $sql = "UPDATE empleados SET estado = '$nuevo_estado' WHERE id = $id";

    if ($conexion->query($sql) === TRUE) {
        $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
        $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
        $descripcion = "El {$rol} {$nombre_user} ha cambiado el estado del empleado {$persona_info} a '{$nuevo_estado}'.";
        registrar_bitacora($conexion, "Cambio de Estado Empleado", $descripcion);

        header("Location: ../vista/ver_empleados.php?cambio=" . $nuevo_estado);
    } else {
        echo "Error actualizando estado: " . $conexion->error;
    }
}
$conexion->close();
?>