<?php
session_start();
require_once 'conexion.php';
require_once 'registrar_bitacora.php'; 

if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_registrar'])) {

    $cedula = trim($_POST['cedula'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $genero = $_POST['genero'] ?? '';
    $comuna = trim($_POST['comuna'] ?? '');
    $categoria = $_POST['categoria'] ?? '';
    $disciplina_id = $_POST['disciplina_id'] ?? '';
    $entrenador_id = $_POST['entrenador_id'] ?? '';
    $representante_id = $_POST['representante_id'] ?? '';

    $stmt_check = $conexion->prepare("SELECT id FROM atletas WHERE cedula = ?");
    $stmt_check->bind_param("s", $cedula);
    $stmt_check->execute();
    $res_check = $stmt_check->get_result();

    if ($res_check->num_rows > 0) {
        $stmt_check->close();
 
        header("Location: ../vista/registrar_atletas.php?error=cedula_existe");
        exit();
    }
    $stmt_check->close();

    $stmt = $conexion->prepare("INSERT INTO atletas (cedula, nombre, apellido, fecha_nacimiento, genero, comuna, categoria, disciplina_id, entrenador_id, representante_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssii", $cedula, $nombre, $apellido, $fecha_nacimiento, $genero, $comuna, $categoria, $disciplina_id, $entrenador_id, $representante_id);

    if ($stmt->execute()) {
        $atleta_id = $stmt->insert_id;

        $id_usuario = $_SESSION['usuario_id'] ?? null;
        $accion = "REGISTRO_ATLETA";
        $descripcion = "Se registró exitosamente al atleta '{$nombre} {$apellido}' (Cédula: {$cedula}) con ID: {$atleta_id}";
        
        if (function_exists('registrar_bitacora')) {
            registrar_bitacora($conexion, $id_usuario, $accion, $descripcion);
        }

        $stmt->close();
        header("Location: ../vista/ver_atletas.php?msg=registrado");
        exit();
    } else {
        $stmt->close();
        header("Location: ../vista/registrar_atletas.php?error=db_error");
        exit();
    }
} else {
    header("Location: ../vista/registrar_atletas.php");
    exit();
}
?>