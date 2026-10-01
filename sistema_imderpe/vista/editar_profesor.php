<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../controlador/controlador_editar_profesor.php';

$error_duplicado =$_GET['error'] ?? '';
$campo_duplicado =$_GET['campo'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Profesor - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style21.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form action="../controlador/controlador_editar_profesor.php" method="POST" class="glass-form" novalidate>
            <input type="hidden" name="id" value="<?php echo $profesor['id']; ?>">
            
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">Modificar Profesor</h2>

            <div class="form-grid">
                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="cedula" id="cedula" value="<?php echo htmlspecialchars($profesor['cedula']); ?>" placeholder="Cédula de Identidad" autocomplete="off">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'cedula'): ?>
                        <span class="error-mensaje error-backend">* esta cédula ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($profesor['nombre']); ?>" placeholder="Nombres" autocomplete="off">
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="apellido" id="apellido" value="<?php echo htmlspecialchars($profesor['apellido']); ?>" placeholder="Apellidos" autocomplete="off">
                </div>

                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="telefono" id="telefono" value="<?php echo htmlspecialchars($profesor['telefono']); ?>" placeholder="Número de Teléfono" autocomplete="off">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'telefono'): ?>
                        <span class="error-mensaje error-backend">* este teléfono ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="correo" id="correo" value="<?php echo htmlspecialchars($profesor['correo']); ?>" placeholder="Correo Electrónico" autocomplete="off">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'correo'): ?>
                        <span class="error-mensaje error-backend">* este correo ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-school"></i>
                    <input type="text" name="instituto_educativo" id="instituto_educativo" value="<?php echo htmlspecialchars($profesor['instituto_educativo']); ?>" placeholder="Instituto Educativo" autocomplete="off">
                </div>
            </div>

            <div class="action-row">
                <button type="submit" class="btn-register">Guardar Cambios</button>
                <a href="ver_profesores.php" class="btn-cancel">Cancelar y Volver</a>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.glass-form');

        // Limpia la URL tras mostrar el error al cargar
        if (window.location.search.includes('error=duplicado')) {
            window.history.replaceState({}, document.title, window.location.pathname + '?id=<?php echo $profesor['id']; ?>');
        }

        // Resalta el campo si viene con un error de backend
        const errorBackend = document.querySelector('.error-backend');
        if (errorBackend) {
            const inputDuplicado = errorBackend.closest('.input-group').querySelector('input');
            if (inputDuplicado) inputDuplicado.classList.add('input-error');
        }

        function ocultarErroresGrupo(parentGroup) {
            parentGroup.querySelectorAll('.error-mensaje').forEach(el => el.remove());
            const input = parentGroup.querySelector('input');
            if (input) input.classList.remove('input-error');
        }

        const todosLosInputs = form.querySelectorAll('input:not([type="hidden"])');
        todosLosInputs.forEach(input => {
            const parent = input.closest('.input-group');
            if (parent) {
                input.addEventListener('input', () => ocultarErroresGrupo(parent));
                input.addEventListener('change', () => ocultarErroresGrupo(parent));
            }
        });

        // Validación previa al Submit
        form.addEventListener('submit', function(e) {
            let hayError = false;

            form.querySelectorAll('.error-mensaje:not(.error-backend)').forEach(el => el.remove());

            todosLosInputs.forEach(campo => {
                const parentGroup = campo.closest('.input-group');
                const val = campo.value ? campo.value.trim() : "";
                
                if (val === "" || val === null) {
                    hayError = true;
                    campo.classList.add('input-error');

                    const errorBackendEnGrupo = parentGroup.querySelector('.error-backend');
                    if (errorBackendEnGrupo) errorBackendEnGrupo.remove();

                    if (!parentGroup.querySelector('.error-mensaje-js')) {
                        let placeholderText = campo.getAttribute('placeholder') ? campo.getAttribute('placeholder').toLowerCase() : 'este campo';
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