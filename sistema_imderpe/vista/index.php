<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style1.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo"></div>
            
            <h2>Acceso Administrativo</h2>

            <?php if (isset($_SESSION['error_login'])): ?>
                <div style="background: rgba(211, 47, 47, 0.4); 
                            color: #fff; 
                            padding: 12px; 
                            border-radius: 8px; 
                            margin-bottom: 20px; 
                            font-size: 0.9rem;
                            font-weight: bold;
                            border: 1px solid #D32F2F;
                            backdrop-filter: blur(5px);">
                    <?php 
                        echo $_SESSION['error_login']; 
                        unset($_SESSION['error_login']); 
                    ?>
                </div>
            <?php endif; ?>

            <form action="../controlador/iniciar_sesion.php" method="POST">
                <div class="input-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo123@correo.com" required>
                </div>
                
                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn-login">Entrar</button>
            </form>
            
            <footer>
                Instituto Autónomo de Deporte y Recreación del Municipio Peña
            </footer>
        </div>
    </div>
</body>
</html>