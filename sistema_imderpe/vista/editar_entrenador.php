<?php
session_start();
require_once '../controlador/conexion.php';

if (!isset($_GET['id'])) {
    header("Location: ver_entrenadores.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conexion->prepare("SELECT * FROM entrenadores WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$entrenador = $resultado->fetch_assoc();

if (!$entrenador) {
    header("Location: ver_entrenadores.php");
    exit();
}

$res_disciplinas = $conexion->query("SELECT id, nombre_disciplina FROM disciplinas ORDER BY nombre_disciplina ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Entrenador - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style13.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form action="../controlador/controlador_editar_entrenador.php" method="POST" class="glass-form">
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">Editar Entrenador</h2>

            <input type="hidden" name="id" value="<?php echo $entrenador['id']; ?>">

            <div class="input-group">
                <i class="fas fa-id-card"></i>
                <input type="text" name="cedula" value="<?php echo $entrenador['cedula']; ?>" placeholder="Cédula" required>
            </div>

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="nombre" value="<?php echo $entrenador['nombre']; ?>" placeholder="Nombre" required>
            </div>

            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="apellido" value="<?php echo $entrenador['apellido']; ?>" placeholder="Apellido" required>
            </div>

            <div class="input-group">
                <i class="fas fa-trophy"></i>
                <select name="disciplina_id" required>
                    <?php while($d = $res_disciplinas->fetch_assoc()): ?>
                        <option value="<?php echo $d['id']; ?>" <?php echo ($d['id'] == $entrenador['disciplina_id']) ? 'selected' : ''; ?>>
                            <?php echo $d['nombre_disciplina']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="telefono" value="<?php echo $entrenador['telefono']; ?>" placeholder="Teléfono" required>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="correo" value="<?php echo $entrenador['correo']; ?>" placeholder="Correo" required>
            </div>

            <div class="input-group">
                <i class="fas fa-toggle-on"></i>
                <select name="estado" required>
                    <option value="activo" <?php echo ($entrenador['estado'] == 'activo') ? 'selected' : ''; ?>>Activo</option>
                    <option value="inactivo" <?php echo ($entrenador['estado'] == 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>

            <button type="submit" name="btn_editar" value="ok" class="btn-register">Guardar Cambios</button>
            <a href="ver_entrenadores.php" class="btn-cancel">Cancelar y Volver</a>
        </form>
    </div>
</body>
</html>