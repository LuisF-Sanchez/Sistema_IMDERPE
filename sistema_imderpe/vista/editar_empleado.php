<?php
session_start();
require_once '../controlador/conexion.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
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
        <form action="../controlador/controlador_editar_empleado.php" method="POST" enctype="multipart/form-data" class="glass-form">
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">Editar Empleado</h2>

            <input type="hidden" name="id" value="<?php echo $empleado['id']; ?>">

            <div class="form-grid">
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
                        <option value="Presidente" <?php echo ($empleado['cargo'] == 'Presidente') ? 'selected' : ''; ?>>Presidente</option>
                        <option value="Administrador" <?php echo ($empleado['cargo'] == 'Administrador') ? 'selected' : ''; ?>>Administrador</option>
                        <option value="Jefe de Planificación" <?php echo ($empleado['cargo'] == 'Jefe de Planificación') ? 'selected' : ''; ?>>Jefe de Planificación</option>
                        <option value="Jefe de la Oficina de la OAC" <?php echo ($empleado['cargo'] == 'Jefe de la Oficina de la OAC') ? 'selected' : ''; ?>>Jefe de la Oficina de la OAC</option>
                        <option value="Promotor Deportivo" <?php echo ($empleado['cargo'] == 'Promotor Deportivo') ? 'selected' : ''; ?>>Promotor Deportivo</option>
                        <option value="Médica" <?php echo ($empleado['cargo'] == 'Médica') ? 'selected' : ''; ?>>Médica</option>
                        <option value="Supervisor Deportivo" <?php echo ($empleado['cargo'] == 'Supervisor Deportivo') ? 'selected' : ''; ?>>Supervisor Deportivo</option>
                        <option value="Asistente Administrativo" <?php echo ($empleado['cargo'] == 'Asistente Administrativo') ? 'selected' : ''; ?>>Asistente Administrativo</option>
                        <option value="Secretaria" <?php echo ($empleado['cargo'] == 'Secretaria') ? 'selected' : ''; ?>>Secretaria</option>
                        <option value="Entrenador Deportivo" <?php echo ($empleado['cargo'] == 'Entrenador Deportivo') ? 'selected' : ''; ?>>Entrenador Deportivo</option>
                        <option value="Analista de RRHH" <?php echo ($empleado['cargo'] == 'Analista de RRHH') ? 'selected' : ''; ?>>Analista de RRHH</option>
                        <option value="Obrero Fijo" <?php echo ($empleado['cargo'] == 'Obrero Fijo') ? 'selected' : ''; ?>>Obrero Fijo</option>
                        <option value="Obrero Contratado" <?php echo ($empleado['cargo'] == 'Obrero Contratado') ? 'selected' : ''; ?>>Obrero Contratado</option>
                    </select>
                </div>

                <div class="input-group">
                    <i class="fas fa-calendar-alt"></i>
                    <input type="date" name="fecha_ingreso" value="<?php echo $empleado['fecha_ingreso']; ?>" title="Fecha de Ingreso">
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
            </div>

            <div class="file-group">
                <label class="file-label" for="foto">
                    <i class="fas fa-camera"></i> Nueva Foto (Opcional)
                </label>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>

            <div class="action-row">
                <button type="submit" class="btn-update">Actualizar Información</button>
                <a href="ver_empleados.php" class="btn-cancel">Cancelar y Volver</a>
            </div>
        </form>
    </div>
</body>
</html>