<?php
session_start();
require_once 'conexion.php';

if (isset($_POST['btn_registrar'])) {
    
    if (
        !empty($_POST['nombre']) && 
        !empty($_POST['apellido']) && 
        !empty($_POST['cedula']) && 
        !empty($_POST['telefono']) && 
        !empty($_POST['correo']) && 
        !empty($_POST['direccion']) && 
        !empty($_POST['comuna'])
    ) {
        
        $nombre    = trim($_POST['nombre']);
        $apellido  = trim($_POST['apellido']);
        $cedula    = trim($_POST['cedula']);
        $telefono  = trim($_POST['telefono']);
        $correo    = trim($_POST['correo']);
        $direccion = trim($_POST['direccion']);
        $comuna    = trim($_POST['comuna']);

        $stmt_check = $conexion->prepare("SELECT id FROM autogobierno WHERE cedula = ?");
        $stmt_check->bind_param("s", $cedula);
        $stmt_check->execute();
        $stmt_check->store_result();
        
        if ($stmt_check->num_rows > 0) {
            $stmt_check->close();
            $conexion->close();
            header("Location: ../vista/ver_autogobierno.php?error_duplicado=1");
            exit();
        }
        $stmt_check->close();

        $sql = "INSERT INTO autogobierno (nombre, apellido, cedula, telefono, correo, direccion, comuna) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conexion->prepare($sql);
        $stmt_insert->bind_param("sssssss", $nombre, $apellido, $cedula, $telefono, $correo, $direccion, $comuna);

        if ($stmt_insert->execute()) {
            $stmt_insert->close();
            $conexion->close();
            header("Location: ../vista/ver_autogobierno.php?registro=exito");
            exit();
        } else {
            $stmt_insert->close();
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