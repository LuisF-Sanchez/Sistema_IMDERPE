<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../controlador/conexion.php';
require_once '../controlador/registrar_bitacora.php';

// Verificar que los datos vengan por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../vista/ver_empleados.php");
    exit();
}

$cedula        = trim($_POST['cedula'] ?? '');
$nombre        = trim($_POST['nombre'] ?? '');
$apellido      = trim($_POST['apellido'] ?? '');
$cargo         = trim($_POST['cargo'] ?? '');
$telefono      = trim($_POST['telefono'] ?? '');
$correo        = trim($_POST['correo'] ?? '');
$estado        = trim($_POST['estado'] ?? '');
$fecha_ingreso = !empty($_POST['fecha_ingreso']) ? $_POST['fecha_ingreso'] : null;

// 1. VALIDACIÓN DE DUPLICADOS (Cédula, Correo o Teléfono)
$sql_check = "SELECT cedula, correo, telefono FROM empleados WHERE cedula = ? OR correo = ? OR telefono = ?";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("sss", $cedula, $correo, $telefono);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    $existente = $result_check->fetch_assoc();
    $stmt_check->close();

    // Identificar cuál fue el campo duplicado para enviar la alerta precisa
    if ($existente['cedula'] === $cedula) {
        $tipo_error = "cedula";
    } elseif ($existente['correo'] === $correo) {
        $tipo_error = "correo";
    } else {
        $tipo_error = "telefono";
    }

    header("Location: ../vista/registrar_empleado.php?error=duplicado&campo=" . $tipo_error);
    exit();
}
$stmt_check->close();

// 2. PROCESAMIENTO DE LA FOTO
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

// 3. INSERCIÓN DEL NUEVO EMPLEADO
$sql = "INSERT INTO empleados (cedula, nombre, apellido, cargo, fecha_ingreso, telefono, correo, estado, foto) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssssss", $cedula, $nombre, $apellido, $cargo, $fecha_ingreso, $telefono, $correo, $estado, $nombre_foto);

if ($stmt->execute()) {
    // Registro en la bitácora del sistema
    $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
    $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
    $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
    $descripcion = "El {$rol} {$nombre_user} ha registrado al empleado {$nombre} {$apellido}{$cedula_txt}.";
    
    registrar_bitacora($conexion, "Registro de Empleado", $descripcion);

    header("Location: ../vista/ver_empleados.php?registro=exito");
    exit(); 
} else {
    echo "Error al registrar: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>