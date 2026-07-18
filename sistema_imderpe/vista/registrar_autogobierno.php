<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Autogobierno - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style18.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="glass-form">
            <img src="../estilo/logo.png" alt="IMDERPE" class="logo-form">
            
            <h2 class="form-title">Registrar Responsable</h2>
            
            <form action="../controlador/controlador_registrar_autogobierno.php" method="POST">
                
                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="cedula" placeholder="Cédula de Identidad" required autocomplete="off">
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nombre" placeholder="Nombres" required autocomplete="off">
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="apellido" placeholder="Apellidos" required autocomplete="off">
                </div>

                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="telefono" placeholder="Número de Teléfono" required autocomplete="off">
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="correo" placeholder="Correo Electrónico" required autocomplete="off">
                </div>

                <div class="input-group">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="direccion" placeholder="Dirección Sala de Autogobierno" required autocomplete="off">
                </div>

                <div class="input-group">
                    <i class="fas fa-users"></i>
                    <input type="text" name="comuna" placeholder="Comuna" required autocomplete="off">
                </div>

                <button type="submit" name="btn_registrar" class="btn-register">
                    Registrar Responsable
                </button>

                <a href="ver_autogobierno.php" class="btn-cancel">Cancelar y Volver</a>
            </form>
        </div>
    </div>
</body>
</html>