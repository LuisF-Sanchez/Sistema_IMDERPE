<?php
require_once '../controlador/conexion.php';

if (isset($_GET['id']) && isset($_GET['actual'])) {
    $id = $_GET['id'];
    $estado_actual = $_GET['actual'];
    

    $nuevo_estado = ($estado_actual == 'activo') ? 'inactivo' : 'activo';

    $sql = "UPDATE empleados SET estado = '$nuevo_estado' WHERE id = $id";

    if ($conexion->query($sql) === TRUE) {

        header("Location: ../vista/ver_empleados.php?cambio=" . $nuevo_estado);
    } else {
        echo "Error actualizando estado: " . $conexion->error;
    }
}
$conexion->close();
?>