<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../controlador/conexion.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT id, cedula, nombre, apellido, correo, direccion FROM representantes WHERE id = $id";
    $resultado = $conexion->query($sql);
    $representante = $resultado->fetch_assoc();

    if (!$representante) {
        header("Location: ver_representantes.php");
        exit();
    }
} else {
    header("Location: ver_representantes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Representante - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style7.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form action="../controlador/controlador_editar_representante.php" method="POST" class="glass-form">
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">Editar Representante</h2>

            <input type="hidden" name="id" value="<?php echo $representante['id']; ?>">

            <div class="input-group">
                <i class="fas fa-id-card"></i>
                <input type="text" name="cedula" value="<?php echo htmlspecialchars($representante['cedula']); ?>" placeholder="Cédula de Identidad" required>
            </div>

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($representante['nombre']); ?>" placeholder="Nombres" required>
            </div>

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="apellido" value="<?php echo htmlspecialchars($representante['apellido']); ?>" placeholder="Apellidos" required>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="correo" value="<?php echo htmlspecialchars($representante['correo']); ?>" placeholder="Correo Electrónico" required>
            </div>

            <div class="input-group">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" name="direccion" value="<?php echo htmlspecialchars($representante['direccion']); ?>" placeholder="Dirección de Habitación" required>
            </div>

            <button type="submit" class="btn-update">Actualizar Información</button>
            <a href="ver_representantes.php" class="btn-cancel">Cancelar y Volver</a>
        </form>
    </div>
</body>
</html>