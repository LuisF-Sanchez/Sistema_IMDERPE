<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../controlador/controlador_editar_profesor.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Profesor - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style21.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form action="../controlador/controlador_editar_profesor.php" method="POST" class="glass-form">
            <input type="hidden" name="id" value="<?php echo $profesor['id']; ?>">
            
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">Modificar Profesor</h2>

            <div class="form-grid">
                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="cedula" value="<?php echo htmlspecialchars($profesor['cedula']); ?>" placeholder="Cédula de Identidad" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($profesor['nombre']); ?>" placeholder="Nombres" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="apellido" value="<?php echo htmlspecialchars($profesor['apellido']); ?>" placeholder="Apellidos" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="telefono" value="<?php echo htmlspecialchars($profesor['telefono']); ?>" placeholder="Número de Teléfono">
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="correo" value="<?php echo htmlspecialchars($profesor['correo']); ?>" placeholder="Correo Electrónico">
                </div>

                <div class="input-group">
                    <i class="fas fa-school"></i>
                    <input type="text" name="instituto_educativo" value="<?php echo htmlspecialchars($profesor['instituto_educativo']); ?>" placeholder="Instituto Educativo" required>
                </div>
            </div>

            <div class="action-row">
                <button type="submit" class="btn-register">Guardar Cambios</button>
                <a href="ver_profesores.php" class="btn-cancel">Cancelar y Volver</a>
            </div>
        </form>
    </div>
</body>
</html>