<?php
$conexion = new mysqli("localhost", "root", "", "imderpe");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $cedula = $_POST['cedula'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña']; 
    $tipo = $_POST['tipo'];

    $tabla = ($tipo == "administrador") ? "administrador" : "usuarios";

    $sql = "INSERT INTO $tabla (nombre, cedula, telefono, correo, contraseña, tipo) 
            VALUES ('$nombre', '$cedula', '$telefono', '$correo', '$contraseña', '$tipo')";

    if ($conexion->query($sql) === TRUE) {
        header("Location: ../vista/administrar_usuarios.php?success=true&tipo=" . $tipo);
        exit();
    } else {
        echo "Error al registrar: " . $conexion->error;
    }
}

$conexion->close();
?>