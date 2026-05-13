<?php
session_start();
require_once '../controlador/conexion.php';

$res_disciplinas = $conexion->query("SELECT id, nombre_disciplina FROM disciplinas ORDER BY nombre_disciplina ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Entrenador - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style12.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form action="../controlador/controlador_registrar_entrenador.php" method="POST" class="glass-form">
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">Registro de Entrenador</h2>

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
                <i class="fas fa-trophy"></i>
                <select name="disciplina_id" required>
                    <option value="" disabled selected>Seleccione Disciplina</option>
                    <?php while($d = $res_disciplinas->fetch_assoc()): ?>
                        <option value="<?php echo $d['id']; ?>">
                            <?php echo htmlspecialchars($d['nombre_disciplina']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
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
                    <option value="activo" selected>Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>

            <button type="submit" name="btn_registrar" value="ok" class="btn-register">Registrar Entrenador</button>
            <a href="ver_entrenadores.php" class="btn-cancel">Cancelar y Volver</a>
        </form>
    </div>
</body>
</html>