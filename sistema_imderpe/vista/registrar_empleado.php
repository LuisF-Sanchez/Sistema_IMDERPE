<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Empleado - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style6.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form action="../controlador/controlador_registrar_empleados.php" method="POST" class="glass-form">
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">Registro de Empleado</h2>

            <div class="input-group">
                <i class="fas fa-id-card"></i>
                <input type="text" name="cedula" placeholder="Cédula de Identidad" required>
            </div>

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="nombre" placeholder="Nombres" required>
            </div>

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="apellido" placeholder="Apellidos" required>
            </div>

            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="telefono" placeholder="Número de Teléfono" required>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="correo" placeholder="Correo Electrónico" required>
            </div>

            <div class="input-group">
                <i class="fas fa-toggle-on"></i>
                <select name="estado" required>
                    <option value="" disabled selected>Seleccione Estado</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>

            <button type="submit" class="btn-register">Registrar Empleado</button>
            <a href="ver_empleados.php" class="btn-cancel">Cancelar y Volver</a>
        </form>
    </div>
</body>
</html>