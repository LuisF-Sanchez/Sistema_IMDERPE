<?php
session_start();
require_once 'conexion.php';

if (!empty($_POST["btn_guardar_tipo"])) {

    if (!empty($_POST["nuevo_tipo"])) {

        $nuevo_tipo = trim($_POST["nuevo_tipo"]);


        $check = $conexion->prepare("SELECT id FROM tipos_actividad WHERE nombre_tipo = ?");
        $check->bind_param("s", $nuevo_tipo);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {

            header("Location: ../vista/registrar_actividades.php?error_duplicado=1");
            exit();
        } else {

            $sql = $conexion->prepare("INSERT INTO tipos_actividad (nombre_tipo) VALUES (?)");
            $sql->bind_param("s", $nuevo_tipo);
            
            if ($sql->execute()) {

                $nuevo_id = $conexion->insert_id;
                
                header("Location: ../vista/registrar_actividades.php?tipo_exito=1&nuevo_tipo_id=" . $nuevo_id);
                exit();
            } else {
                header("Location: ../vista/registrar_actividades.php?error=1");
                exit();
            }
            $sql->close();
        }
        $check->close();
    } else {
        header("Location: ../vista/registrar_actividades.php?error_campos=1");
        exit();
    }
}

$conexion->close();
?>