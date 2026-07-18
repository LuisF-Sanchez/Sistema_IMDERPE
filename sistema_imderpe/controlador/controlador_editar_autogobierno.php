<?php
session_start();
require_once 'conexion.php';

if (isset($_POST['btn_editar'])) {
    
    if (
        !empty($_POST['id']) &&
        !empty($_POST['nombre']) && 
        !empty($_POST['apellido']) && 
        !empty($_POST['cedula']) && 
        !empty($_POST['telefono']) && 
        !empty($_POST['correo']) && 
        !empty($_POST['direccion']) && 
        !empty($_POST['comuna'])
    ) {
        
        $id        = intval($_POST['id']);
        $nombre    = trim($_POST['nombre']);
        $apellido  = trim($_POST['apellido']);
        $cedula    = trim($_POST['cedula']);
        $telefono  = trim($_POST['telefono']);
        $correo    = trim($_POST['correo']);
        $direccion = trim($_POST['direccion']);
        $comuna    = trim($_POST['comuna']);

        $stmt_check = $conexion->prepare("SELECT id FROM autogobierno WHERE cedula = ? AND id != ?");
        $stmt_check->bind_param("si", $cedula, $id);
        $stmt_check->execute();
        $stmt_check->store_result();
        
        if ($stmt_check->num_rows > 0) {
            $stmt_check->close();
            $conexion->close();
            header("Location: ../vista/ver_autogobierno.php?error_duplicado=1");
            exit();
        }
        $stmt_check->close();


        $sql = "UPDATE autogobierno 
                SET nombre = ?, apellido = ?, cedula = ?, telefono = ?, correo = ?, direccion = ?, comuna = ? 
                WHERE id = ?";
                
        $stmt_update = $conexion->prepare($sql);
        $stmt_update->bind_param("sssssssi", $nombre, $apellido, $cedula, $telefono, $correo, $direccion, $comuna, $id);

        if ($stmt_update->execute()) {
            $stmt_update->close();
            $conexion->close();
            header("Location: ../vista/ver_autogobierno.php?edit_exito=1");
            exit();
        } else {
            $stmt_update->close();
            $conexion->close();
            header("Location: ../vista/ver_autogobierno.php?error_db=1");
            exit();
        }

    } else {
        $conexion->close();
        header("Location: ../vista/ver_autogobierno.php?error_campos=1");
        exit();
    }
} else {
    header("Location: ../vista/ver_autogobierno.php");
    exit();
}
?>