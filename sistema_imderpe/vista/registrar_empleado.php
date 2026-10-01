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
    <title>Registrar Empleado - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style6.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form action="../controlador/controlador_registrar_empleados.php" method="POST" enctype="multipart/form-data" class="glass-form" novalidate>
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">Registro de Empleado</h2>

            <div class="form-grid">
                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="cedula" placeholder="Cédula de Identidad">
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nombre" placeholder="Nombres">
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="apellido" placeholder="Apellidos">
                </div>

                <div class="input-group">
                    <i class="fas fa-briefcase"></i>
                    <select name="cargo">
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
                    <input type="text" name="telefono" placeholder="Número de Teléfono">
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="correo" placeholder="Correo Electrónico">
                </div>

                <div class="input-group">
                    <i class="fas fa-toggle-on"></i>
                    <select name="estado">
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
                <?php if (isset($_GET['error']) &&$_GET['error'] === 'duplicado'): ?>
                    <div class="alert-duplicado" id="alertaDuplicado">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>
                            <?php 
                                $campo =$_GET['campo'] ?? '';
                                if ($campo === 'cedula') echo "Ya existe un empleado registrado con esa Cédula.";
                                elseif ($campo === 'correo') echo "Ya existe un empleado registrado con ese Correo Electrónico.";
                                elseif ($campo === 'telefono') echo "Ya existe un empleado registrado con ese Número de Teléfono.";
                                else echo "Los datos ingresados ya coinciden con un empleado existente.";
                            ?>
                        </span>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn-register">Registrar Empleado</button>
                <a href="ver_empleados.php" class="btn-cancel">Cancelar y Volver</a>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.glass-form');
        const alertaDuplicado = document.getElementById('alertaDuplicado');

        if (window.location.search.includes('error=duplicado')) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        function ocultarAlertaDuplicado() {
            if (alertaDuplicado) {
                alertaDuplicado.remove();
            }
        }

        const todosLosInputs = form.querySelectorAll('input, select');
        todosLosInputs.forEach(input => {
            input.addEventListener('input', ocultarAlertaDuplicado);
            input.addEventListener('change', ocultarAlertaDuplicado);
        });

        form.addEventListener('submit', function(e) {
            let hayError = false;

            document.querySelectorAll('.error-mensaje').forEach(el => el.remove());
            document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

            const campos = form.querySelectorAll('.form-grid input, .form-grid select');

            campos.forEach(campo => {
                if (!campo.value || campo.value.trim() === "") {
                    hayError = true;
                    campo.classList.add('input-error');

                    let mensaje = "* Debe colocar este campo";

                    if (campo.tagName === 'SELECT') {
                        let textoOpcion = campo.options[0].text.replace('Seleccione ', '');
                        mensaje = `* Debe seleccionar ${textoOpcion.toLowerCase()}`;
                    } else if (campo.type === 'date') {
                        mensaje = "* Debe seleccionar la fecha de ingreso";
                    } else if (campo.getAttribute('placeholder')) {
                        let placeholderText = campo.getAttribute('placeholder').toLowerCase();
                        mensaje = `* Debe colocar ${placeholderText}`;
                    }

                    const msgError = document.createElement('span');
                    msgError.className = 'error-mensaje';
                    msgError.innerHTML = mensaje;

                    const parentGroup = campo.closest('.input-group');
                    parentGroup.appendChild(msgError);
                }
            });

            if (hayError) {
                ocultarAlertaDuplicado();
                e.preventDefault();
            }
        });
    });
    </script>
</body>
</html>