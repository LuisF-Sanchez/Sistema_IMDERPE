<?php
session_start();
require_once 'conexion.php';

if (!empty($_POST["btn_guardar_tipo"])) {
    // 1. Validar que el campo no venga vacío
    if (!empty($_POST["nuevo_tipo"])) {
        // Limpiamos espacios en blanco de los lados
        $nuevo_tipo = trim($_POST["nuevo_tipo"]);

        // 2. Verificar que no exista ya ese tipo para evitar duplicados exactos
        $check = $conexion->prepare("SELECT id FROM tipos_actividad WHERE nombre_tipo = ?");
        $check->bind_param("s", $nuevo_tipo);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            // Si ya existe, regresamos a la vista con la alerta de duplicado
            header("Location: ../vista/registrar_actividades.php?error_duplicado=1");
            exit();
        } else {
            // 3. Insertar el nuevo tipo de actividad
            $sql = $conexion->prepare("INSERT INTO tipos_actividad (nombre_tipo) VALUES (?)");
            $sql->bind_param("s", $nuevo_tipo);
            
            if ($sql->execute()) {
                // CAPTURA CLAVE: Obtenemos el ID numérico que la base de datos le asignó automáticamente
                $nuevo_id = $conexion->insert_id;
                
                // Redirigimos pasándole el parámetro de éxito y el nuevo ID
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