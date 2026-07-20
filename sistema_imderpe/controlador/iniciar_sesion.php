<?php
session_start();
require_once 'conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $correo = $conexion->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $resultado = $conexion->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        if ($password === $usuario['contraseña']) {
            
            $_SESSION['usuario_id']     = $usuario['id']; 
            $_SESSION['usuario_cedula'] = $usuario['cedula']; 
            $_SESSION['usuario_nombre'] = $usuario['nombre']; 
            $_SESSION['usuario_tipo']   = $usuario['tipo']; 

            header("Location: ../vista/inicio.php");
            exit();

        } else {
            $_SESSION['error_login'] = "Contraseña incorrecta";
            header("Location: ../vista/index.php");
            exit();
        }
    } else {
        $_SESSION['error_login'] = "El correo electrónico no está registrado";
        header("Location: ../vista/index.php");
        exit();
    }
}

$conexion->close();
?>