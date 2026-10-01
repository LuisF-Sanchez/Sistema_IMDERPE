<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../controlador/conexion.php';

$id_responsable = $_GET['id'] ?? '';

if (empty($id_responsable)) {
    header("Location: ver_autogobierno.php");
    exit();
}

$stmt =$conexion->prepare("SELECT nombre, apellido, cedula, telefono, correo, direccion, comuna FROM autogobierno WHERE id = ?");
$stmt->bind_param("i", $id_responsable);$stmt->execute();
$resultado =$stmt->get_result();

if ($resultado->num_rows === 0) {
    $stmt->close();$conexion->close();
    header("Location: ver_autogobierno.php");
    exit();
}

$responsable = $resultado->fetch_assoc();$stmt->close();

$error_duplicado =$_GET['error'] ?? '';
$campo_duplicado =$_GET['campo'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Responsable - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style25.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form action="../controlador/controlador_editar_autogobierno.php" method="POST" class="glass-form" novalidate>
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="IMDERPE" class="logo-form">
            </div>
            
            <h2 class="form-title">Editar Responsable</h2>
            
            <input type="hidden" name="id" value="<?php echo $id_responsable; ?>">

            <div class="form-grid">
                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="cedula" id="cedula" placeholder="Cédula de Identidad" autocomplete="off" value="<?php echo htmlspecialchars($responsable['cedula']); ?>">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'cedula'): ?>
                        <span class="error-mensaje error-backend">* esta cédula ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombres" autocomplete="off" value="<?php echo htmlspecialchars($responsable['nombre']); ?>">
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="apellido" id="apellido" placeholder="Apellidos" autocomplete="off" value="<?php echo htmlspecialchars($responsable['apellido']); ?>">
                </div>

                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="telefono" id="telefono" placeholder="Número de Teléfono" autocomplete="off" value="<?php echo htmlspecialchars($responsable['telefono']); ?>">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'telefono'): ?>
                        <span class="error-mensaje error-backend">* este teléfono ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="correo" id="correo" placeholder="Correo Electrónico" autocomplete="off" value="<?php echo htmlspecialchars($responsable['correo']); ?>">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'correo'): ?>
                        <span class="error-mensaje error-backend">* este correo ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-users"></i>
                    <input type="text" name="comuna" id="comuna" placeholder="Comuna" autocomplete="off" value="<?php echo htmlspecialchars($responsable['comuna']); ?>">
                </div>

                <div class="input-group full-width">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="direccion" id="direccion" placeholder="Dirección Sala de Autogobierno" autocomplete="off" value="<?php echo htmlspecialchars($responsable['direccion']); ?>">
                </div>
            </div>

            <div class="action-row">
                <button type="submit" name="btn_editar" value="ok" class="btn-update">
                    Actualizar Información
                </button>
                <a href="ver_autogobierno.php" class="btn-cancel">Cancelar y Volver</a>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.glass-form');

        // Limpia los parámetros de error de la URL
        if (window.location.search.includes('error=duplicado')) {
            window.history.replaceState({}, document.title, window.location.pathname + '?id=<?php echo $id_responsable; ?>');
        }

        // Si vino marcado un error backend, resalta el input correspondientes
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

        // Validación frontend al enviar
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
<?php
$conexion->close();
?>