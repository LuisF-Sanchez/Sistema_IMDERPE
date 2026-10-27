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

        // Datos unificados para la bitácora idénticos al resto de módulos
        $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Administrador');
        $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
        
        $accion = "Registro de Atleta";
        $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
        $descripcion = "El {$rol} {$nombre_user} ha registrado al atleta {$nombre} {$apellido}{$cedula_txt}.";
        
        if (function_exists('registrar_bitacora')) {
            // Nota: Dependiendo de cómo reciba los parámetros tu función registrar_bitacora(), 
            // asegúrate de pasarle los argumentos en el orden correcto que espera. 
            // Si tu función global usa (conexion, accion, descripcion), pásalos así:
            registrar_bitacora($conexion, $accion, $descripcion);
        }

        $stmt->close();
        header("Location: ../vista/ver_atletas.php?registro_exito=1");
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