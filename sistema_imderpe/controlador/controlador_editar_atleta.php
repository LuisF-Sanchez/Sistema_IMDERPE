<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../controlador/conexion.php';
require_once '../controlador/registrar_bitacora.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    
    $sql_atleta = $conexion->query("SELECT * FROM atletas WHERE id = $id");
    $atleta = $sql_atleta->fetch_assoc();

    if (!$atleta) {
        header("Location: ../vista/ver_atletas.php");
        exit();
    }

    $res_disciplinas = $conexion->query("SELECT id, nombre_disciplina FROM disciplinas ORDER BY nombre_disciplina ASC");
    $res_entrenadores = $conexion->query("SELECT id, nombre, apellido FROM entrenadores ORDER BY nombre ASC");
    $res_representantes = $conexion->query("SELECT id, nombre, apellido FROM representantes ORDER BY nombre ASC");
}

if (!empty($_POST["btn_actualizar"])) {
    $id = intval($_POST['id']);
    $cedula = mysqli_real_escape_string($conexion, $_POST['cedula']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $genero = mysqli_real_escape_string($conexion, $_POST['genero']);
    $fecha = $_POST['fecha_nacimiento'];
    $comuna = mysqli_real_escape_string($conexion, $_POST['comuna']);
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']);
    $representante_id = intval($_POST['representante_id']);
    $entrenador_id = intval($_POST['entrenador_id']);
    $disciplina_id = intval($_POST['disciplina_id']);
    $estado = $_POST['estado'];

    $sql = $conexion->query("UPDATE atletas SET 
        cedula = '$cedula', 
        nombre = '$nombre', 
        apellido = '$apellido', 
        genero = '$genero', 
        fecha_nacimiento = '$fecha', 
        comuna = '$comuna',
        categoria = '$categoria',
        representante_id = $representante_id, 
        entrenador_id = $entrenador_id, 
        disciplina_id = $disciplina_id, 
        estado = '$estado' 
        WHERE id = $id");

    if ($sql) {
        $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
        $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
        $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
        $descripcion = "El {$rol} {$nombre_user} ha actualizado los datos del atleta {$nombre} {$apellido}{$cedula_txt}.";
        registrar_bitacora($conexion, "Edición de Atleta", $descripcion);

        header("Location: ../vista/ver_atletas.php?edit_exito=1");
        exit();
    } else {
        header("Location: ../vista/ver_atletas.php?error=1");
        exit();
    }
}
?>