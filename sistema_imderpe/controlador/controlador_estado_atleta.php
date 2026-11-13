<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../controlador/conexion.php'; 
require_once '../controlador/registrar_bitacora.php';

$pagina_actual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$disciplina_filtro = isset($_GET['disciplina']) ? $_GET['disciplina'] : '';
$genero_filtro = isset($_GET['genero']) ? $_GET['genero'] : '';
$categoria_filtro = isset($_GET['categoria']) ? $_GET['categoria'] : '';

$filtros_query = "";
if (!empty($disciplina_filtro)) $filtros_query .= "&disciplina=" . urlencode($disciplina_filtro);
if (!empty($genero_filtro)) $filtros_query .= "&genero=" . urlencode($genero_filtro);
if (!empty($categoria_filtro)) $filtros_query .= "&categoria=" . urlencode($categoria_filtro);

if (isset($_GET['id']) && isset($_GET['actual'])) {
    $id = intval($_GET['id']);
    $estado_actual = $_GET['actual'];
    
    $nuevo_estado = ($estado_actual == 'activo') ? 'inactivo' : 'activo';

    $persona_info = "ID: {$id}";
    $stmt_info = $conexion->prepare("SELECT nombre, apellido, cedula FROM atletas WHERE id = ?");
    if ($stmt_info) {
        $stmt_info->bind_param("i", $id);
        $stmt_info->execute();
        $res = $stmt_info->get_result();
        if ($row = $res->fetch_assoc()) {
            $cedula = !empty($row['cedula']) ? " (C.I. {$row['cedula']})" : "";
            $persona_info = "{$row['nombre']} {$row['apellido']}{$cedula}";
        }
        $stmt_info->close();
    }

    $stmt = $conexion->prepare("UPDATE atletas SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $id);

    if ($stmt->execute()) {
        $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
        $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
        $descripcion = "El {$rol} {$nombre_user} ha cambiado el estado del atleta {$persona_info} a '{$nuevo_estado}'.";
        registrar_bitacora($conexion, "Cambio de Estado Atleta", $descripcion);

        header("Location: ../vista/ver_atletas.php?estado_exito=1&pagina=" . $pagina_actual . $filtros_query);
    } else {
        header("Location: ../vista/ver_atletas.php?error=1&pagina=" . $pagina_actual . $filtros_query);
    }
    
    $stmt->close();
    $conexion->close();
} else {
    header("Location: ../vista/ver_atletas.php?pagina=" . $pagina_actual . $filtros_query);
}
?>