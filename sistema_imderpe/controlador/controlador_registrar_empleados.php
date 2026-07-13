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

// Lógica para procesar la subida de la Foto de Perfil
$nombre_foto = "defaultavatar.png"; // Nombre por defecto si no se sube nada

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $file_tmp  = $_FILES['foto']['tmp_name'];
    $file_name = $_FILES['foto']['name'];
    
    // Extraer la extensión del archivo (ej: .jpg, .png)
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    
    // Generar un nombre único basado en la cédula para evitar conflictos o duplicados
    $nombre_foto = "empleado_" . $cedula . "_" . time() . "." . $ext;
    
    // Carpeta destino donde se guardarán las fotos físicas de los empleados
    $directorio_destino = "../fotos_empleados/";
    
    // Crear el directorio si no existe en el servidor
    if (!is_dir($directorio_destino)) {
        mkdir($directorio_destino, 0777, true);
    }
    
    // Mover el archivo temporal de forma segura a la carpeta final
    move_uploaded_file($file_tmp, $directorio_destino . $nombre_foto);
}

// Preparamos la inserción usando Prepared Statements para evitar fallos de sintaxis por comillas o fechas nulas
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