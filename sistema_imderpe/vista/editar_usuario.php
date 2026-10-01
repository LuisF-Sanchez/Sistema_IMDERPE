<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../controlador/conexion.php';

if (empty($_GET['id'])) {
    header("Location: administrar_usuarios.php");
    exit();
}

$id_usuario = intval($_GET['id']);

$stmt =$conexion->prepare("SELECT nombre, cedula, telefono, correo, tipo FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_usuario);$stmt->execute();
$resultado =$stmt->get_result();

if ($resultado->num_rows === 0) {
    $stmt->close();$conexion->close();
    header("Location: administrar_usuarios.php");
    exit();
}

$usuario = $resultado->fetch_assoc();$stmt->close();

$error_duplicado = isset($_GET['error']) ? $_GET['error'] : '';$campo_duplicado = isset($_GET['campo']) ?$_GET['campo'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style22.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form action="../controlador/controlador_editar_usuario.php" method="POST" class="glass-form" novalidate>
            <input type="hidden" name="id" value="<?php echo $id_usuario; ?>">

            <div class="logo-container">
                <img src="../estilo/logo.png" alt="IMDERPE" class="logo-form">
            </div>
            
            <h2 class="form-title">Modificar Usuario</h2>

            <div class="form-grid">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombre Completo" autocomplete="off" value="<?php echo htmlspecialchars($usuario['nombre']); ?>">
                </div>

                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="cedula" id="cedula" placeholder="Cédula de Identidad" autocomplete="off" value="<?php echo htmlspecialchars($usuario['cedula']); ?>">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'cedula'): ?>
                        <span class="error-mensaje error-backend">* esta cédula ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="telefono" id="telefono" placeholder="Número de Teléfono" autocomplete="off" value="<?php echo htmlspecialchars($usuario['telefono']); ?>">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'telefono'): ?>
                        <span class="error-mensaje error-backend">* este teléfono ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="correo" id="correo" placeholder="Correo Electrónico" autocomplete="off" value="<?php echo htmlspecialchars($usuario['correo']); ?>">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'correo'): ?>
                        <span class="error-mensaje error-backend">* este correo ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group" style="grid-column: span 2;">
                    <i class="fas fa-user-shield"></i>
                    <select name="tipo" id="tipo" class="select-input">
                        <option value="" disabled>Seleccione Rol</option>
                        <option value="usuario" <?php echo ($usuario['tipo'] == 'usuario') ? 'selected' : ''; ?>>Usuario Estándar</option>
                        <option value="administrador" <?php echo ($usuario['tipo'] == 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                    </select>
                </div>
            </div>

            <div class="action-row">
                <button type="submit" name="btn_editar" class="btn-register">Guardar Cambios</button>
                <a href="administrar_usuarios.php" class="btn-cancel">Cancelar y Volver</a>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.glass-form');

        if (window.location.search.includes('error=duplicado')) {
            window.history.replaceState({}, document.title, window.location.pathname + '?id=<?php echo $id_usuario; ?>');
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

        const todosLosInputs = form.querySelectorAll('input:not([type="hidden"]), select');
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

            todosLosInputs.forEach(campo => {
                const parentGroup = campo.closest('.input-group');
                const val = campo.value ? campo.value.trim() : "";
                
                if (val === "" || val === null) {
                    hayError = true;
                    campo.classList.add('input-error');

                    const errorBackendEnGrupo = parentGroup.querySelector('.error-backend');
                    if (errorBackendEnGrupo) errorBackendEnGrupo.remove();

                    if (!parentGroup.querySelector('.error-mensaje-js')) {
                        let textoEtiqueta = campo.tagName.toLowerCase() === 'select' ? 'un rol' : (campo.getAttribute('placeholder') ? campo.getAttribute('placeholder').toLowerCase() : 'este campo');
                        let mensaje = `* Debe colocar ${textoEtiqueta}`;

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