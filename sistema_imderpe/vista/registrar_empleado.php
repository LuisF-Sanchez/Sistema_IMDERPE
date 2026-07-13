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
        <form action="../controlador/controlador_registrar_empleados.php" method="POST" enctype="multipart/form-data" class="glass-form">
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">Registro de Empleado</h2>

            <div class="form-grid">
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
                    <i class="fas fa-briefcase"></i>
                    <select name="cargo" required>
                        <option value="" disabled selected>Seleccione Cargo</option>
                        <option value="Por asignar">Por asignar</option>
                        <option value="Presidente">Presidente</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Jefe de Planificación">Jefe de Planificación</option>
                        <option value="Jefe de la Oficina de la OAC">Jefe de la Oficina de la OAC</option>
                        <option value="Promotor Deportivo">Promotor Deportivo</option>
                        <option value="Médica">Médica</option>
                        <option value="Supervisor Deportivo">Supervisor Deportivo</option>
                        <option value="Asistente Administrativo">Asistente Administrativo</option>
                        <option value="Secretaria">Secretaria</option>
                        <option value="Entrenador Deportivo">Entrenador Deportivo</option>
                        <option value="Analista de RRHH">Analista de RRHH</option>
                        <option value="Obrero Fijo">Obrero Fijo</option>
                        <option value="Obrero Contratado">Obrero Contratado</option>
                    </select>
                </div>

                <div class="input-group">
                    <i class="fas fa-calendar-alt"></i>
                    <input type="date" name="fecha_ingreso" title="Fecha de Ingreso">
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
            </div>

            <div class="input-group file-group">
                <label class="file-label" for="foto">
                    <i class="fas fa-camera"></i> Foto de Perfil (Opcional)
                </label>
                <input type="file" id="foto" name="foto" accept="image/*">
            </div>

            <div class="action-row">
                <button type="submit" class="btn-register">Registrar Empleado</button>
                <a href="ver_empleados.php" class="btn-cancel">Cancelar y Volver</a>
            </div>
        </form>
    </div>
</body>
</html>