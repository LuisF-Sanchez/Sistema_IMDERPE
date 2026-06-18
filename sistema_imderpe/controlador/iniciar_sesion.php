<?php
session_start();

$servidor = "localhost";
$usuario_db = "root"; 
$password_db = ""; 
$nombre_db = "imderpe";

$conexion = new mysqli($servidor, $usuario_db, $password_db, $nombre_db);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $correo = $conexion->real_escape_string($_POST['email']);
    $password = $_POST['password'];


    $sql_admin = "SELECT * FROM administrador WHERE correo = '$correo'";
    $res_admin = $conexion->query($sql_admin);

    if ($res_admin->num_rows > 0) {
        $admin = $res_admin->fetch_assoc();
        
        if ($password == $admin['contraseña']) {
            $_SESSION['admin_id'] = $admin['id']; 
            $_SESSION['usuario_cedula'] = $admin['cedula']; 
            $_SESSION['usuario_nombre'] = $admin['nombre']; 
            $_SESSION['usuario_tipo'] = 'administrador'; 
            
            header("Location: ../vista/inicio.php");
            exit();
        } else {
            $_SESSION['error_login'] = "Contraseña incorrecta";
            header("Location: ../vista/index.php");
            exit();
        }
    } else {

        $sql_user = "SELECT * FROM usuarios WHERE correo = '$correo'";
        $res_user = $conexion->query($sql_user);

        if ($res_user->num_rows > 0) {
            $user = $res_user->fetch_assoc();

            if ($password == $user['contraseña']) {
                $_SESSION['user_id'] = $user['id']; 
                $_SESSION['usuario_cedula'] = $user['cedula']; 
                $_SESSION['usuario_nombre'] = $user['nombre']; 
                $_SESSION['usuario_tipo'] = 'usuario';


                header("Location: ../vista/inicio_usuario.php");
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
}

$conexion->close();
?>