<?php
session_start();
require_once 'conexion.php';

if (!empty($_POST["btn_registrar"])) {

    if (!empty($_POST["nombre_actividad"]) && !empty($_POST["fecha"]) && !empty($_POST["lugar"]) && !empty($_POST["tipo_id"]) && !empty($_POST["empleado_id"])) {
        
        $nombre_actividad = $_POST["nombre_actividad"];
        $fecha = $_POST["fecha"];
        $lugar = $_POST["lugar"];
        $tipo_id = intval($_POST["tipo_id"]);
        $empleado_id = intval($_POST["empleado_id"]);

        $sql = $conexion->prepare("INSERT INTO actividades (nombre_actividad, fecha, lugar, tipo_id, empleado_id) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("sssii", $nombre_actividad, $fecha, $lugar, $tipo_id, $empleado_id);

        if ($sql->execute()) {

            header("Location: ../vista/registrar_actividades.php?registro_exito=1");
            exit();
        } else {

            header("Location: ../vista/registrar_actividades.php?error=1");
            exit();
        }
        $sql->close();
    } else {

        header("Location: ../vista/registrar_actividades.php?error_campos=1");
        exit();
    }
}

$conexion->close();
?>