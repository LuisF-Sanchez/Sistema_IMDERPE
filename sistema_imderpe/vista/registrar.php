<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuario - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form action="../controlador/registrar_usuario.php" method="POST" class="glass-form">
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">Registro de Usuario</h2>

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="nombre" placeholder="Nombre Completo" required>
            </div>

            <div class="input-group">
                <i class="fas fa-id-card"></i>
                <input type="text" name="cedula" placeholder="Cédula" required>
            </div>

            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="telefono" placeholder="Teléfono" required>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="correo" placeholder="Correo Electrónico" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="contraseña" placeholder="Contraseña" required>
            </div>

            <div class="input-group">
                <i class="fas fa-user-tag"></i>
                <select name="tipo" required>
                    <option value="" disabled selected>Seleccione Rol</option>
                    <option value="usuario">Usuario</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>

            <button type="submit" class="btn-register">Registrar Usuario</button>
            <a href="administrar_usuarios.php" class="btn-cancel">Cancelar</a>
        </form>
    </div>
</body>
</html>