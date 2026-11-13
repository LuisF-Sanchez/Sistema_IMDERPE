<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../controlador/conexion.php';

$pagina_actual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM entrenadores WHERE id = $id";
    $resultado = $conexion->query($sql);
    $entrenador = $resultado->fetch_assoc();

    if (!$entrenador) {
        header("Location: ver_entrenadores.php?pagina=" . $pagina_actual);
        exit();
    }

    $res_disciplinas = $conexion->query("SELECT id, nombre_disciplina FROM disciplinas ORDER BY nombre_disciplina ASC");
} else {
    header("Location: ver_entrenadores.php?pagina=" . $pagina_actual);
    exit();
}

$error_duplicado = $_GET['error'] ?? '';
$campo_duplicado = $_GET['campo'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Entrenador - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style7.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form action="../controlador/controlador_editar_entrenador.php" method="POST" class="glass-form" novalidate>
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">EDITAR ENTRENADOR</h2>

            <input type="hidden" name="id" value="<?php echo $entrenador['id']; ?>">
            <input type="hidden" name="pagina" value="<?php echo $pagina_actual; ?>">

            <div class="form-grid">
                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="cedula" id="cedula" value="<?php echo htmlspecialchars($entrenador['cedula']); ?>" placeholder="Cédula de Identidad">
                    <?php if ($error_duplicado === 'duplicado' && $campo_duplicado === 'cedula'): ?>
                        <span class="error-mensaje error-backend">* Esta cédula ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($entrenador['nombre']); ?>" placeholder="Nombre">
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="apellido" id="apellido" value="<?php echo htmlspecialchars($entrenador['apellido']); ?>" placeholder="Apellido">
                </div>

                <div class="input-group">
                    <i class="fas fa-dumbbell"></i>
                    <select name="disciplina_id" id="disciplina_id">
                        <option value="" disabled>Seleccione Disciplina</option>
                        <?php while($disc = $res_disciplinas->fetch_assoc()): ?>
                            <option value="<?php echo $disc['id']; ?>" <?php echo ($entrenador['disciplina_id'] == $disc['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($disc['nombre_disciplina']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="telefono" id="telefono" value="<?php echo htmlspecialchars($entrenador['telefono']); ?>" placeholder="Número de Teléfono">
                    <?php if ($error_duplicado === 'duplicado' && $campo_duplicado === 'telefono'): ?>
                        <span class="error-mensaje error-backend">* Este teléfono ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="correo" id="correo" value="<?php echo htmlspecialchars($entrenador['correo']); ?>" placeholder="Correo Electrónico">
                    <?php if ($error_duplicado === 'duplicado' && $campo_duplicado === 'correo'): ?>
                        <span class="error-mensaje error-backend">* Este correo ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-toggle-on"></i>
                    <select name="estado" id="estado">
                        <option value="activo" <?php echo ($entrenador['estado'] == 'activo') ? 'selected' : ''; ?>>Activo</option>
                        <option value="inactivo" <?php echo ($entrenador['estado'] == 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="action-row">
                <button type="submit" name="btn_editar" value="ok" class="btn-update">ACTUALIZAR INFORMACIÓN</button>
                <a href="ver_entrenadores.php?pagina=<?php echo $pagina_actual; ?>" class="btn-cancel">Cancelar y Volver</a>
            </div>
        </form>
    </div>
</body>
</html>