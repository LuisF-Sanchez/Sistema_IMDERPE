<?php
require_once 'conexion.php';

if (!empty($_POST["btn_editar"])) {

    if (!empty($_POST["id"]) && !empty($_POST["cedula"]) && !empty($_POST["nombre"]) && !empty($_POST["apellido"])) {
        
        $id = intval($_POST["id"]);
        $cedula = $_POST["cedula"];
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $disciplina_id = $_POST["disciplina_id"];
        $telefono = $_POST["telefono"];
        $correo = $_POST["correo"];
        $estado = $_POST["estado"];

        $check_cedula = $conexion->query("SELECT id FROM entrenadores WHERE cedula = '$cedula' AND id != $id");
        
        if ($check_cedula->num_rows > 0) {
            header("Location: ../vista/ver_entrenadores.php?error_duplicado=1");
            exit();
        }

        $sql = $conexion->prepare("UPDATE entrenadores SET cedula=?, nombre=?, apellido=?, disciplina_id=?, telefono=?, correo=?, estado=? WHERE id=?");
        $sql->bind_param("sssisssi", $cedula, $nombre, $apellido, $disciplina_id, $telefono, $correo, $estado, $id);

        if ($sql->execute()) {

            header("Location: ../vista/ver_entrenadores.php?edit_exito=1");
            exit();
        } else {

            header("Location: ../vista/ver_entrenadores.php?error=1");
            exit();
        }
        $sql->close();

    } else {

        header("Location: ../vista/ver_entrenadores.php?error_campos=1");
        exit();
    }
}

$conexion->close();
?>