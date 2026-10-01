<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'conexion.php';
require_once 'registrar_bitacora.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id            = intval($_POST['id']);
    $cedula        = trim($_POST['cedula']);
    $nombre        = trim($_POST['nombre']);
    $apellido      = trim($_POST['apellido']);
    $cargo         = trim($_POST['cargo']);
    $telefono      = trim($_POST['telefono']);
    $correo        = trim($_POST['correo']);
    $estado        = trim($_POST['estado']);
    $fecha_ingreso = !empty($_POST['fecha_ingreso']) ? $_POST['fecha_ingreso'] : null;

    $check = $conexion->prepare("SELECT id FROM empleados WHERE cedula = ? AND id != ?");
    $check->bind_param("si", $cedula, $id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $check->close();
        header("Location: ../vista/editar_empleado.php?id={$id}&error=duplicado&campo=cedula");
        exit();
    }
    $check->close();

    $check = $conexion->prepare("SELECT id FROM empleados WHERE telefono = ? AND id != ?");
    $check->bind_param("si", $telefono, $id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $check->close();
        header("Location: ../vista/editar_empleado.php?id={$id}&error=duplicado&campo=telefono");
        exit();
    }
    $check->close();

    $check = $conexion->prepare("SELECT id FROM empleados WHERE correo = ? AND id != ?");
    $check->bind_param("si", $correo, $id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $check->close();
        header("Location: ../vista/editar_empleado.php?id={$id}&error=duplicado&campo=correo");
        exit();
    }
    $check->close();

    $query_foto = $conexion->prepare("SELECT foto FROM empleados WHERE id = ?");
    $query_foto->bind_param("i", $id);
    $query_foto->execute();
    $resultado_foto = $query_foto->get_result()->fetch_assoc();
    $nombre_foto = $resultado_foto['foto']; 
    $query_foto->close();

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['foto']['tmp_name'];
        $file_name = $_FILES['foto']['name'];
        $ext       = pathinfo($file_name, PATHINFO_EXTENSION);
        
        $nombre_foto = "empleado_" . $cedula . "_" . time() . "." . $ext;
        $directorio_destino = "../fotos_empleados/";
        
        if (!is_dir($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }
        
        move_uploaded_file($file_tmp, $directorio_destino . $nombre_foto);
    }

    $stmt = $conexion->prepare("UPDATE empleados SET cedula=?, nombre=?, apellido=?, cargo=?, fecha_ingreso=?, telefono=?, correo=?, estado=?, foto=? WHERE id=?");
    $stmt->bind_param("sssssssssi", $cedula, $nombre, $apellido, $cargo, $fecha_ingreso, $telefono, $correo, $estado, $nombre_foto, $id);

    if ($stmt->execute()) {
        $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
        $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
        $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
        $descripcion = "El {$rol} {$nombre_user} ha actualizado los datos del empleado {$nombre} {$apellido}{$cedula_txt}.";
        registrar_bitacora($conexion, "Edición de Empleado", $descripcion);

        header("Location: ../vista/ver_empleados.php?edit_exito=true");
        exit();
    } else {
        echo "Error al actualizar los datos: " . $stmt->error;
    }

    $stmt->close();
}
$conexion->close();
?>