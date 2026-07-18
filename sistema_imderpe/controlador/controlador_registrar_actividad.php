<?php
session_start();
require_once 'conexion.php';

if (!empty($_POST["btn_registrar"])) {

    if (!empty($_POST["nombre_actividad"]) && !empty($_POST["fecha"]) && !empty($_POST["lugar"]) && !empty($_POST["tipo_id"]) && !empty($_POST["empleado_id"])) {
        
        $nombre_actividad = $_POST["nombre_actividad"];
        $fecha            = $_POST["fecha"];
        $lugar            = $_POST["lugar"];
        $tipo_id          = intval($_POST["tipo_id"]);
        $resena           = !empty($_POST["resena"]) ? $_POST["resena"] : null;
        $responsables     = $_POST["empleado_id"];

        $nombre_foto = null;

        if (isset($_FILES['foto_actividad']) && $_FILES['foto_actividad']['error'] === UPLOAD_ERR_OK) {
            $file_tmp  = $_FILES['foto_actividad']['tmp_name'];
            $file_name = $_FILES['foto_actividad']['name'];
            $ext       = pathinfo($file_name, PATHINFO_EXTENSION);
            
            $nombre_foto = "actividad_" . time() . "." . $ext;
            $directorio_destino = "../fotos_actividades/";
            
            if (!is_dir($directorio_destino)) {
                mkdir($directorio_destino, 0777, true);
            }
            
            move_uploaded_file($file_tmp, $directorio_destino . $nombre_foto);
        }

        $sql = $conexion->prepare("INSERT INTO actividades (nombre_actividad, fecha, lugar, tipo_id, resena, foto_actividad) VALUES (?, ?, ?, ?, ?, ?)");
        $sql->bind_param("sssiss", $nombre_actividad, $fecha, $lugar, $tipo_id, $resena, $nombre_foto);

        if ($sql->execute()) {
            $actividad_id = $conexion->insert_id;
            
            $responsables_unicos = array_unique($responsables);
            
            $sql_resp = $conexion->prepare("INSERT INTO actividad_responsables (actividad_id, empleado_id) VALUES (?, ?)");
            foreach ($responsables_unicos as $emp_id) {
                $id_empleado_int = intval($emp_id);
                if ($id_empleado_int > 0) {
                    $sql_resp->bind_param("ii", $actividad_id, $id_empleado_int);
                    $sql_resp->execute();
                }
            }
            $sql_resp->close();

            header("Location: ../vista/inicio.php?registro_actividad_exito=1");
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