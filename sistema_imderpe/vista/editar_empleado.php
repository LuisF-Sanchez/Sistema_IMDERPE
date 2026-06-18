<?php
session_start();
require_once '../controlador/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM empleados WHERE id = $id";
    $resultado = $conexion->query($sql);
    $empleado = $resultado->fetch_assoc();

    if (!$empleado) {
        header("Location: ver_empleados.php");
        exit();
    }
} else {
    header("Location: ver_empleados.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style7.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form action="../controlador/controlador_editar_empleado.php" method="POST" class="glass-form">
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">Editar Empleado</h2>

            <input type="hidden" name="id" value="<?php echo $empleado['id']; ?>">

            <div class="input-group">
                <i class="fas fa-id-card"></i>
                <input type="text" name="cedula" value="<?php echo htmlspecialchars($empleado['cedula']); ?>" placeholder="Cédula de Identidad" required>
            </div>

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($empleado['nombre']); ?>" placeholder="Nombres" required>
            </div>

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="apellido" value="<?php echo htmlspecialchars($empleado['apellido']); ?>" placeholder="Apellidos" required>
            </div>

            <div class="input-group">
                <i class="fas fa-briefcase"></i>
                <select name="cargo" required>
                    <option value="Por asignar" <?php echo ($empleado['cargo'] == 'Por asignar') ? 'selected' : ''; ?>>Por asignar</option>
                    <option value="Administrativo" <?php echo ($empleado['cargo'] == 'Administrativo') ? 'selected' : ''; ?>>Administrativo</option>
                    <option value="Instructor" <?php echo ($empleado['cargo'] == 'Instructor') ? 'selected' : ''; ?>>Instructor</option>
                    <option value="Coordinador" <?php echo ($empleado['cargo'] == 'Coordinador') ? 'selected' : ''; ?>>Coordinador</option>
                </select>
            </div>

            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="telefono" value="<?php echo htmlspecialchars($empleado['telefono']); ?>" placeholder="Número de Teléfono" required>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="correo" value="<?php echo htmlspecialchars($empleado['correo']); ?>" placeholder="Correo Electrónico" required>
            </div>

            <div class="input-group">
                <i class="fas fa-toggle-on"></i>
                <select name="estado" required>
                    <option value="activo" <?php echo ($empleado['estado'] == 'activo') ? 'selected' : ''; ?>>Activo</option>
                    <option value="inactivo" <?php echo ($empleado['estado'] == 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>

            <button type="submit" class="btn-update">Actualizar Información</button>
            <a href="ver_empleados.php" class="btn-cancel">Cancelar y Volver</a>
        </form>
    </div>
</body>
</html>