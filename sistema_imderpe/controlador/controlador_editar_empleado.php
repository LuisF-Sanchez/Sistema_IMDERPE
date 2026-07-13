<?php
require_once 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id            = intval($_POST['id']);
    $cedula        = $_POST['cedula'];
    $nombre        = $_POST['nombre'];
    $apellido      = $_POST['apellido'];
    $cargo         = $_POST['cargo'];
    $telefono      = $_POST['telefono'];
    $correo        = $_POST['correo'];
    $estado        = $_POST['estado'];
    $fecha_ingreso = !empty($_POST['fecha_ingreso']) ? $_POST['fecha_ingreso'] : null;

    // 1. Validar que la cédula o correo no pertenezcan a OTRO empleado distinto
    $check = $conexion->prepare("SELECT id FROM empleados WHERE (cedula = ? OR correo = ?) AND id != ?");
    $check->bind_param("ssi", $cedula, $correo, $id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows > 0) {
        header("Location: ../vista/ver_empleados.php?error_duplicado=true");
        exit();
    }
    $check->close();

    // 2. Obtener la foto actual por si no se sube ninguna nueva
    $query_foto = $conexion->prepare("SELECT foto FROM empleados WHERE id = ?");
    $query_foto->bind_param("i", $id);
    $query_foto->execute();
    $resultado_foto = $query_foto->get_result()->fetch_assoc();
    $nombre_foto = $resultado_foto['foto']; 
    $query_foto->close();

    // 3. Procesar la nueva foto si fue enviada en el formulario
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['foto']['tmp_name'];
        $file_name = $_FILES['foto']['name'];
        $ext       = pathinfo($file_name, PATHINFO_EXTENSION);
        
        // Generar un nombre único basado en la cédula
        $nombre_foto = "empleado_" . $cedula . "_" . time() . "." . $ext;
        $directorio_destino = "../fotos_empleados/";
        
        if (!is_dir($directorio_destino)) {
            mkdir($directorio_destino, 0777, true);
        }
        
        move_uploaded_file($file_tmp, $directorio_destino . $nombre_foto);
    }

    // 4. Ejecutar la actualización completa mediante Prepared Statements
    $stmt = $conexion->prepare("UPDATE empleados SET cedula=?, nombre=?, apellido=?, cargo=?, fecha_ingreso=?, telefono=?, correo=?, estado=?, foto=? WHERE id=?");
    $stmt->bind_param("sssssssssi", $cedula, $nombre, $apellido, $cargo, $fecha_ingreso, $telefono, $correo, $estado, $nombre_foto, $id);

    if ($stmt->execute()) {
        header("Location: ../vista/ver_empleados.php?edit_exito=true");
        exit();
    } else {
        echo "Error al actualizar los datos: " . $stmt->error;
    }

    $stmt->close();
}
$conexion->close();
?>