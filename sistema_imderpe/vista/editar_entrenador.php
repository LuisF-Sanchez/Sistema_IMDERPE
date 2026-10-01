<?php
session_start();
require_once '../controlador/conexion.php';

if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header("Location: ver_entrenadores.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : intval($_POST['id']);

$stmt =$conexion->prepare("SELECT * FROM entrenadores WHERE id = ?");
$stmt->bind_param("i", $id);$stmt->execute();
$resultado =$stmt->get_result();
$entrenador =$resultado->fetch_assoc();

if (!$entrenador) {
    header("Location: ver_entrenadores.php");
    exit();
}

$res_disciplinas =$conexion->query("SELECT id, nombre_disciplina FROM disciplinas ORDER BY nombre_disciplina ASC");

$error_duplicado =$_GET['error'] ?? '';
$campo_duplicado =$_GET['campo'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Entrenador - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style13.css">
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

            <div class="form-grid">
                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="cedula" id="cedula" value="<?php echo htmlspecialchars($entrenador['cedula']); ?>" placeholder="Cédula de Identidad">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'cedula'): ?>
                        <span class="error-mensaje error-backend">* esta cédula ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($entrenador['nombre']); ?>" placeholder="Nombres">
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="apellido" id="apellido" value="<?php echo htmlspecialchars($entrenador['apellido']); ?>" placeholder="Apellidos">
                </div>

                <div class="input-group">
                    <i class="fas fa-trophy"></i>
                    <select name="disciplina_id" id="disciplina_id">
                        <option value="" disabled>Seleccione Disciplina</option>
                        <?php while($d =$res_disciplinas->fetch_assoc()): ?>
                            <option value="<?php echo $d['id']; ?>" <?php echo ($d['id'] ==$entrenador['disciplina_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($d['nombre_disciplina']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="telefono" id="telefono" value="<?php echo htmlspecialchars($entrenador['telefono']); ?>" placeholder="Número de Teléfono">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'telefono'): ?>
                        <span class="error-mensaje error-backend">* este teléfono ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="correo" id="correo" value="<?php echo htmlspecialchars($entrenador['correo']); ?>" placeholder="Correo Electrónico">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'correo'): ?>
                        <span class="error-mensaje error-backend">* este correo ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group full-width">
                    <i class="fas fa-toggle-on"></i>
                    <select name="estado" id="estado">
                        <option value="activo" <?php echo ($entrenador['estado'] == 'activo') ? 'selected' : ''; ?>>Estado: Activo</option>
                        <option value="inactivo" <?php echo ($entrenador['estado'] == 'inactivo') ? 'selected' : ''; ?>>Estado: Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="action-row">
                <button type="submit" name="btn_editar" value="ok" class="btn-update">ACTUALIZAR INFORMACIÓN</button>
                <a href="ver_entrenadores.php" class="btn-cancel">Cancelar y Volver</a>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.glass-form');

        if (window.location.search.includes('error=duplicado')) {
            window.history.replaceState({}, document.title, window.location.pathname + '?id=<?php echo $entrenador['id']; ?>');
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