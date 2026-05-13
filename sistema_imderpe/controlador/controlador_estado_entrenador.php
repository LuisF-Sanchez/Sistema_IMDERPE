<?php
require_once 'conexion.php';

if (isset($_GET['id']) && isset($_GET['actual'])) {
    $id = intval($_GET['id']);
    $estado_actual = $_GET['actual'];

    $nuevo_estado = ($estado_actual == 'activo') ? 'inactivo' : 'activo';


    $stmt = $conexion->prepare("UPDATE entrenadores SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_estado, $id);

    if ($stmt->execute()) {

        header("Location: ../vista/ver_entrenadores.php?estado_exito=1");
        exit();
    } else {

        header("Location: ../vista/ver_entrenadores.php?error=1");
        exit();
    }

    $stmt->close();
} else {

    header("Location: ../vista/ver_entrenadores.php");
    exit();
}

$conexion->close();
?>