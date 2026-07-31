<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../controlador/conexion.php';
require_once '../controlador/registrar_bitacora.php';

if (!empty($_POST["btn_registrar"])) {
    $cedula = mysqli_real_escape_string($conexion, $_POST['cedula']);
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    $fecha = $_POST['fecha_nacimiento'];
    $genero = mysqli_real_escape_string($conexion, $_POST['genero']); 
    $comuna = mysqli_real_escape_string($conexion, $_POST['comuna']); 
    $categoria = mysqli_real_escape_string($conexion, $_POST['categoria']); 
    $representante_id = intval($_POST['representante_id']);
    $entrenador_id = intval($_POST['entrenador_id']);
    $disciplina_id = intval($_POST['disciplina_id']);
    $estado = 'activo';

    $sql = $conexion->query("INSERT INTO atletas (cedula, nombre, apellido, fecha_nacimiento, genero, comuna, categoria, representante_id, entrenador_id, disciplina_id, estado) 
                             VALUES ('$cedula', '$nombre', '$apellido', '$fecha', '$genero', '$comuna', '$categoria', $representante_id, $entrenador_id, $disciplina_id, '$estado')");

    if ($sql) {
        $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
        $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
        $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
        $descripcion = "El {$rol} {$nombre_user} ha registrado al atleta {$nombre} {$apellido}{$cedula_txt}.";
        registrar_bitacora($conexion, "Registro de Atleta", $descripcion);

        header("Location: ../vista/ver_atletas.php?registro_exito=1");
    } else {
        header("Location: ../vista/ver_atletas.php?error=1");
    }
    exit();
}
?>