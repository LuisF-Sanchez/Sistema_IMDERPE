<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../controlador/conexion.php';

// Consultamos los empleados activos para el select de Responsable
$res_empleados = $conexion->query("SELECT id, nombre, apellido FROM empleados WHERE estado = 'activo' ORDER BY nombre ASC");

// Consultamos los tipos de actividades existentes para el select
// Nota: Es recomendable tener una tabla 'tipos_actividad' para que sea dinámico, o usar los ENUM del sistema.
// Para permitir registrar nuevos tipos en caliente, asumiremos que tienes una tabla 'tipos_actividad' (id, nombre_tipo).
$res_tipos = $conexion->query("SELECT id, nombre_tipo FROM tipos_actividad ORDER BY nombre_tipo ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Actividad - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style14.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="glass-form">
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">Registro de Actividad</h2>

            <div class="extra-action-container">
                <button type="button" class="btn-secondary" id="btn-toggle-tipo">
                    <i class="fas fa-plus-circle"></i> Nuevo Tipo de Actividad
                </button>
                
                <div class="mini-form-collapse" id="mini-form-tipo" style="display: none;">
                    <form action="../controlador/controlador_registrar_tipo_actividad.php" method="POST">
                        <div class="input-group">
                            <i class="fas fa-tags"></i>
                            <input type="text" name="nuevo_tipo" placeholder="Nombre del nuevo tipo (Ej: Reunión)" required>
                        </div>
                        <button type="submit" name="btn_guardar_tipo" value="ok" class="btn-save-mini">Guardar y Seleccionar</button>
                    </form>
                </div>
            </div>

            <form action="../controlador/controlador_registrar_actividad.php" method="POST">
                
                <div class="input-group">
                    <i class="fas fa-running"></i>
                    <input type="text" name="nombre_actividad" placeholder="Nombre de la Actividad" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-calendar-alt"></i>
                    <input type="date" name="fecha" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="lugar" placeholder="Lugar o Instalación" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-layer-group"></i>
                    <select name="tipo_id" id="tipo_id" required>
                        <option value="" disabled selected>Seleccione Tipo de Actividad</option>
                        <?php if($res_tipos && $res_tipos->num_rows > 0): ?>
                            <?php while($t = $res_tipos->fetch_assoc()): ?>
                                <option value="<?php echo $t['id']; ?>" <?php echo (isset($_GET['nuevo_tipo_id']) && $_GET['nuevo_tipo_id'] == $t['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($t['nombre_tipo']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="recreativa">Recreativa</option>
                            <option value="deportiva">Deportiva</option>
                            <option value="limpieza">Limpieza</option>
                            <option value="mantenimiento">Mantenimiento</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="input-group">
                    <i class="fas fa-user-shield"></i>
                    <select name="empleado_id" required>
                        <option value="" disabled selected>Seleccione Responsable</option>
                        <?php while($e = $res_empleados->fetch_assoc()): ?>
                            <option value="<?php echo $e['id']; ?>">
                                <?php echo htmlspecialchars($e['nombre'] . " " . $e['apellido']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <button type="submit" name="btn_registrar" value="ok" class="btn-register">Registrar Actividad</button>
                <a href="inicio.php" class="btn-cancel">Cancelar y Volver</a>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('btn-toggle-tipo').addEventListener('click', function() {
            var miniForm = document.getElementById('mini-form-tipo');
            if (miniForm.style.display === 'none') {
                miniForm.style.display = 'block';
                this.innerHTML = '<i class="fas fa-times-circle"></i> Cerrar Formulario';
                this.style.background = '#dc3545';
            } else {
                miniForm.style.display = 'none';
                this.innerHTML = '<i class="fas fa-plus-circle"></i> Nuevo Tipo de Actividad';
                this.style.background = '#17a2b8';
            }
        });
    </script>
</body>
</html>