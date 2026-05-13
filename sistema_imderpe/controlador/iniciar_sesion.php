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


    $sql = "SELECT * FROM administrador WHERE correo = '$correo'";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $admin = $resultado->fetch_assoc();
        
if ($password == $admin['contraseña']) {
    $_SESSION['admin_id'] = $admin['cedula']; 
    $_SESSION['usuario_nombre'] = $admin['nombre']; 
    
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