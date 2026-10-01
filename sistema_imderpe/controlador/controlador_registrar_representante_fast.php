<?php
session_start();
require_once 'conexion.php';
require_once 'registrar_bitacora.php';

header('Content-Type: application/json');

$cedula = trim($_POST['cedula'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');

$errores = [];

// 1. Validar si la CÉDULA ya existe
if (!empty($cedula)) {
    $stmt = $conexion->prepare("SELECT id FROM representantes WHERE cedula = ?");
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errores['cedula'] = "Esta cédula ya está registrada";
    }
    $stmt->close();
}

// 2. Validar si el TELÉFONO ya existe
if (!empty($telefono)) {
    $stmt = $conexion->prepare("SELECT id FROM representantes WHERE telefono = ?");
    $stmt->bind_param("s", $telefono);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errores['telefono'] = "El número de teléfono ya está registrado";
    }
    $stmt->close();
}

// 3. Validar si el CORREO ya existe
if (!empty($correo)) {
    $stmt = $conexion->prepare("SELECT id FROM representantes WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errores['correo'] = "El correo ya está registrado";
    }
    $stmt->close();
}

// Si hay uno o más errores, se devuelven todos al mismo tiempo
if (!empty($errores)) {
    echo json_encode(['success' => false, 'errors' => $errores]);
    exit();
}

// 4. Inserción de nuevo representante
$stmt_insert = $conexion->prepare("INSERT INTO representantes (cedula, nombre, apellido, telefono, correo, direccion) VALUES (?, ?, ?, ?, ?, ?)");
$stmt_insert->bind_param("ssssss", $cedula, $nombre, $apellido, $telefono, $correo, $direccion);

if ($stmt_insert->execute()) {
    $nuevo_id = $stmt_insert->insert_id;

    // Registrar en la bitácora
    $id_usuario = $_SESSION['usuario_id'] ?? null;
    $accion = "REGISTRO_REPRESENTANTE_RAPIDO";
    $descripcion = "Se registró rápidamente al representante '{$nombre} {$apellido}' (Cédula: {$cedula})";

    if (function_exists('registrar_bitacora')) {
        registrar_bitacora($conexion, $id_usuario, $accion, $descripcion);
    }

    echo json_encode([
        'success' => true,
        'id'      => $nuevo_id,
        'cedula'  => $cedula,
        'nombre'  => $nombre . " " . $apellido
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error al guardar en la base de datos.']);
}

$stmt_insert->close();
?>