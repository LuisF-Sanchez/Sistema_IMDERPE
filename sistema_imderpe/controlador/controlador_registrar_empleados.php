<?php

require_once '../controlador/conexion.php';

$cedula        = $_POST['cedula'];
$nombre        = $_POST['nombre'];
$apellido      = $_POST['apellido'];
$cargo         = $_POST['cargo'];
$telefono      = $_POST['telefono'];
$correo        = $_POST['correo'];
$estado        = $_POST['estado'];
$fecha_ingreso = !empty($_POST['fecha_ingreso']) ? $_POST['fecha_ingreso'] : null;

$nombre_foto = "defaultavatar.png"; 

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $file_tmp  = $_FILES['foto']['tmp_name'];
    $file_name = $_FILES['foto']['name'];
    
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    
    $nombre_foto = "empleado_" . $cedula . "_" . time() . "." . $ext;
    
    $directorio_destino = "../fotos_empleados/";
    
    if (!is_dir($directorio_destino)) {
        mkdir($directorio_destino, 0777, true);
    }
    
    move_uploaded_file($file_tmp, $directorio_destino . $nombre_foto);
}

$sql = "INSERT INTO empleados (cedula, nombre, apellido, cargo, fecha_ingreso, telefono, correo, estado, foto) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssssss", $cedula, $nombre, $apellido, $cargo, $fecha_ingreso, $telefono, $correo, $estado, $nombre_foto);

if ($stmt->execute()) {
    header("Location: ../vista/ver_empleados.php?registro=exito");
    exit(); 
} else {
    echo "Error al registrar: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>