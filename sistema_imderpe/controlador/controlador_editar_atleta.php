<?php

session_start();
require_once '../controlador/conexion.php';

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
    $fecha = $_POST['fecha_nacimiento'];
    

    $representante_id = intval($_POST['representante_id']);
    $entrenador_id = intval($_POST['entrenador_id']);
    $disciplina_id = intval($_POST['disciplina_id']);
    $estado = $_POST['estado'];


    $sql = $conexion->query("UPDATE atletas SET 
        cedula = '$cedula', 
        nombre = '$nombre', 
        apellido = '$apellido', 
        fecha_nacimiento = '$fecha', 
        representante_id = $representante_id, 
        entrenador_id = $entrenador_id, 
        disciplina_id = $disciplina_id, 
        estado = '$estado' 
        WHERE id = $id");

    if ($sql) {

        header("Location: ../vista/ver_atletas.php?edit_exito=1");
        exit();
    } else {

        header("Location: ../vista/ver_atletas.php?error=1");
        exit();
    }
}
?>