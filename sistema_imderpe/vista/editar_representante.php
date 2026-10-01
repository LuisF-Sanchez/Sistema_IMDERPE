<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../controlador/conexion.php';

if (isset($_GET['id'])) {$id = intval($_GET['id']);$sql = "SELECT id, cedula, nombre, apellido, telefono, correo, direccion FROM representantes WHERE id = $id";
    $resultado = $conexion->query($sql);
    $representante =$resultado->fetch_assoc();

    if (!$representante) {
        header("Location: ver_representantes.php");
        exit();
    }
} else {
    header("Location: ver_representantes.php");
    exit();
}

$error_duplicado =$_GET['error'] ?? '';
$campo_duplicado =$_GET['campo'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Representante - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style7.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form action="../controlador/controlador_editar_representante.php" method="POST" class="glass-form" novalidate>
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">EDITAR REPRESENTANTE</h2>

            <input type="hidden" name="id" value="<?php echo $representante['id']; ?>">

            <div class="form-grid">
                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="cedula" id="cedula" value="<?php echo htmlspecialchars($representante['cedula']); ?>" placeholder="Cédula de Identidad">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'cedula'): ?>
                        <span class="error-mensaje error-backend">* Esta cédula ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($representante['nombre']); ?>" placeholder="Nombre">
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="apellido" id="apellido" value="<?php echo htmlspecialchars($representante['apellido']); ?>" placeholder="Apellido">
                </div>

                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="telefono" id="telefono" value="<?php echo htmlspecialchars($representante['telefono'] ?? ''); ?>" placeholder="Número de Teléfono">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'telefono'): ?>
                        <span class="error-mensaje error-backend">* Este número de teléfono ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="correo" id="correo" value="<?php echo htmlspecialchars($representante['correo']); ?>" placeholder="Correo Electrónico">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'correo'): ?>
                        <span class="error-mensaje error-backend">* Este correo ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="direccion" id="direccion" value="<?php echo htmlspecialchars($representante['direccion']); ?>" placeholder="Dirección">
                </div>
            </div>

            <div class="action-row">
                <button type="submit" class="btn-update">ACTUALIZAR INFORMACIÓN</button>
                <a href="ver_representantes.php" class="btn-cancel">Cancelar y Volver</a>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.glass-form');

        // Limpia los parámetros de error de la URL sin recargar la página, manteniendo el id
        if (window.location.search.includes('error=duplicado')) {
            window.history.replaceState({}, document.title, window.location.pathname + '?id=<?php echo $representante['id']; ?>');
        }

        // Resalta el borde en rojo del input que devolvió error desde el controlador PHP
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

        // Al escribir en cualquier input, se remueve el mensaje de error activo
        const todosLosInputs = form.querySelectorAll('input');
        todosLosInputs.forEach(input => {
            const parent = input.closest('.input-group');
            if (parent) {
                input.addEventListener('input', () => ocultarErroresGrupo(parent));
            }
        });

        // Validación frontend al hacer submit (campos vacíos)
        form.addEventListener('submit', function(e) {
            let hayError = false;

            form.querySelectorAll('.error-mensaje:not(.error-backend)').forEach(el => el.remove());

            const campos = form.querySelectorAll('.form-grid input');

            campos.forEach(campo => {
                const parentGroup = campo.closest('.input-group');
                
                if (!campo.value || campo.value.trim() === "") {
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