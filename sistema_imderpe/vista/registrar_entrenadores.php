<?php
session_start();
require_once '../controlador/conexion.php';

$res_disciplinas =$conexion->query("SELECT id, nombre_disciplina FROM disciplinas ORDER BY nombre_disciplina ASC");

$error_duplicado =$_GET['error'] ?? '';
$campo_duplicado =$_GET['campo'] ?? '';
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
        <form action="../controlador/controlador_registrar_entrenador.php" method="POST" class="glass-form" novalidate>
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">Registro de Entrenador</h2>

            <div class="form-grid">
                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="cedula" id="cedula" placeholder="Cédula de Identidad">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'cedula'): ?>
                        <span class="error-mensaje error-backend">* esta cédula ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombres">
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="apellido" id="apellido" placeholder="Apellidos">
                </div>

                <div class="input-group">
                    <i class="fas fa-trophy"></i>
                    <select name="disciplina_id" id="disciplina_id">
                        <option value="" disabled selected>Seleccione Disciplina</option>
                        <?php while($d =$res_disciplinas->fetch_assoc()): ?>
                            <option value="<?php echo $d['id']; ?>">
                                <?php echo htmlspecialchars($d['nombre_disciplina']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="telefono" id="telefono" placeholder="Número de Teléfono">
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="correo" id="correo" placeholder="Correo Electrónico">
                </div>

                <div class="input-group full-width">
                    <i class="fas fa-toggle-on"></i>
                    <select name="estado" id="estado">
                        <option value="activo" selected>Estado: Activo</option>
                        <option value="inactivo">Estado: Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="action-row">
                <button type="submit" name="btn_registrar" value="ok" class="btn-register">Registrar Entrenador</button>
                <a href="ver_entrenadores.php" class="btn-cancel">Cancelar y Volver</a>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.glass-form');

        if (window.location.search.includes('error=duplicado')) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        const errorBackend = document.querySelector('.error-backend');
        if (errorBackend) {
            const inputDuplicado = errorBackend.closest('.input-group').querySelector('input, select');
            if (inputDuplicado) inputDuplicado.classList.add('input-error');
        }

        function ocultarErroresGrupo(parentGroup) {
            parentGroup.querySelectorAll('.error-mensaje').forEach(el => el.remove());
            const input = parentGroup.querySelector('input, select');
            if (input) input.classList.remove('input-error');
        }

        const todosLosInputs = form.querySelectorAll('input, select');
        todosLosInputs.forEach(input => {
            const parent = input.closest('.input-group');
            if (parent) {
                input.addEventListener('input', () => ocultarErroresGrupo(parent));
                input.addEventListener('change', () => ocultarErroresGrupo(parent));
            }
        });

        form.addEventListener('submit', function(e) {
            let hayError = false;

            form.querySelectorAll('.error-mensaje:not(.error-backend)').forEach(el => el.remove());

            const campos = form.querySelectorAll('.form-grid input, .form-grid select');

            campos.forEach(campo => {
                const parentGroup = campo.closest('.input-group');
                const val = campo.value ? campo.value.trim() : "";
                
                if (val === "" || val === null) {
                    hayError = true;
                    campo.classList.add('input-error');

                    const errorBackendEnGrupo = parentGroup.querySelector('.error-backend');
                    if (errorBackendEnGrupo) errorBackendEnGrupo.remove();

                    if (!parentGroup.querySelector('.error-mensaje-js')) {
                        let placeholderText = campo.getAttribute('placeholder') ? campo.getAttribute('placeholder').toLowerCase() : 'este campo';
                        
                        if (campo.tagName === 'SELECT') {
                            placeholderText = 'una opción';
                        }

                        let mensaje = `* Debe colocar ${placeholderText}`;

                        const msgError = document.createElement('span');
                        msgError.className = 'error-mensaje error-mensaje-js';
                        msgError.innerText = mensaje;

                        parentGroup.appendChild(msgError);
                    }
                }
            });

            if (hayError) {
                e.preventDefault();
            }
        });
    });
    </script>
</body>
</html>