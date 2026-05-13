<?php
require_once '../controlador/conexion.php'; 

if (isset($_GET['id']) && isset($_GET['actual'])) {
    $id = intval($_GET['id']);
    $estado_actual = $_GET['actual'];
    
    $nuevo_estado = ($estado_actual == 'activo') ? 'inactivo' : 'activo';

    $stmt = $conexion->prepare("UPDATE atletas SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $id);

    if ($stmt->execute()) {
        header("Location: ../vista/ver_atletas.php?estado_exito=1");
    } else {
        header("Location: ../vista/ver_atletas.php?error=1");
    }
    
    $stmt->close();
    $conexion->close();
} else {
    header("Location: ../vista/ver_atletas.php");
}
?>