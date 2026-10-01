<?php
session_start();

require_once '../controlador/controlador_editar_atleta.php';

if (!isset($atleta)) {
    header("Location: ver_atletas.php");
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
    <title>Editar Atleta - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style7.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form action="../controlador/controlador_editar_atleta.php" method="POST" class="glass-form" novalidate>
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">EDITAR ATLETA</h2>

            <input type="hidden" name="id" value="<?php echo $atleta['id']; ?>">

            <div class="form-grid">
                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="cedula" id="cedula" value="<?php echo htmlspecialchars($atleta['cedula']); ?>" placeholder="Cédula de Identidad">
                    <?php if ($error_duplicado === 'duplicado' &&$campo_duplicado === 'cedula'): ?>
                        <span class="error-mensaje error-backend">* esta cedula ya existe</span>
                    <?php endif; ?>
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($atleta['nombre']); ?>" placeholder="Nombre">
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="apellido" id="apellido" value="<?php echo htmlspecialchars($atleta['apellido']); ?>" placeholder="Apellido">
                </div>

                <div class="input-group">
                    <i class="fas fa-venus-mars"></i>
                    <select name="genero" id="genero">
                        <option value="" disabled>Seleccione Género</option>
<option value="Masculino" <?php echo ($atleta['genero'] == 'Masculino' || $atleta['genero'] == 'M') ? 'selected' : ''; ?>>Masculino</option>
<option value="Femenino" <?php echo ($atleta['genero'] == 'Femenino' || $atleta['genero'] == 'F') ? 'selected' : ''; ?>>Femenino</option>
                    </select>
                </div>

                <div class="input-group">
                    <i class="fas fa-calendar-alt"></i>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo $atleta['fecha_nacimiento']; ?>" title="Fecha de Nacimiento">
                </div>

                <div class="input-group">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="comuna" id="comuna" value="<?php echo htmlspecialchars($atleta['comuna']); ?>" placeholder="Comuna">
                </div>

                <div class="input-group">
                    <i class="fas fa-layer-group"></i>
                    <select name="categoria" id="categoria">
                        <option value="" disabled>Seleccione Categoría</option>
                        <option value="Infantil" <?php echo (strtolower($atleta['categoria']) == 'infantil') ? 'selected' : ''; ?>>Infantil</option>
                        <option value="Juvenil" <?php echo (strtolower($atleta['categoria']) == 'juvenil') ? 'selected' : ''; ?>>Juvenil</option>
                    </select>
                </div>

                <div class="input-group">
                    <i class="fas fa-user-shield"></i>
                    <select name="representante_id" id="representante_id">
                        <option value="" disabled>Seleccione Representante Legal</option>
                        <?php 
                        $res_representantes->data_seek(0);
                        while($rep =$res_representantes->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $rep['id']; ?>" 
                                <?php echo ($atleta['representante_id'] ==$rep['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($rep['nombre'] . " " . $rep['apellido']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="input-group">
                    <i class="fas fa-user-tie"></i>
                    <select name="entrenador_id" id="entrenador_id">
                        <option value="" disabled>Seleccione Entrenador Asignado</option>
                        <?php 
                        $res_entrenadores->data_seek(0);
                        while($ent =$res_entrenadores->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $ent['id']; ?>" 
                                <?php echo ($atleta['entrenador_id'] ==$ent['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($ent['nombre'] . " " . $ent['apellido']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="input-group">
                    <i class="fas fa-running"></i>
                    <select name="disciplina_id" id="disciplina_id">
                        <option value="" disabled>Seleccione Disciplina Deportiva</option>
                        <?php 
                        $res_disciplinas->data_seek(0);
                        while($disc =$res_disciplinas->fetch_assoc()): 
                        ?>
                            <option value="<?php echo $disc['id']; ?>" 
                                <?php echo ($atleta['disciplina_id'] ==$disc['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($disc['nombre_disciplina']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="input-group">
                    <i class="fas fa-toggle-on"></i>
                    <select name="estado" id="estado">
                        <option value="" disabled>Seleccione Estado</option>
                        <option value="activo" <?php echo ($atleta['estado'] == 'activo') ? 'selected' : ''; ?>>Activo</option>
                        <option value="inactivo" <?php echo ($atleta['estado'] == 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="action-row">
                <button type="submit" name="btn_actualizar" value="ok" class="btn-update">ACTUALIZAR INFORMACIÓN</button>
                <a href="ver_atletas.php" class="btn-cancel">Cancelar y Volver</a>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.glass-form');

        if (window.location.search.includes('error=duplicado')) {
            window.history.replaceState({}, document.title, window.location.pathname + '?id=<?php echo $atleta['id']; ?>');
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
                
                if (!campo.value || campo.value.trim() === "") {
                    hayError = true;
                    campo.classList.add('input-error');

                    const errorBackendEnGrupo = parentGroup.querySelector('.error-backend');
                    if (errorBackendEnGrupo) errorBackendEnGrupo.remove();

                    if (!parentGroup.querySelector('.error-mensaje-js')) {
                        let mensaje = "* Debe colocar este campo";

                        if (campo.tagName === 'SELECT') {
                            let textoOpcion = campo.options[0].text.replace('Seleccione ', '');
                            mensaje = `* Debe seleccionar ${textoOpcion.toLowerCase()}`;
                        } else if (campo.type === 'date') {
                            mensaje = "* Debe seleccionar la fecha de nacimiento";
                        } else if (campo.getAttribute('placeholder')) {
                            let placeholderText = campo.getAttribute('placeholder').toLowerCase();
                            mensaje = `* Debe colocar ${placeholderText}`;
                        }

                        const msgError = document.createElement('span');
                        msgError.className = 'error-mensaje error-mensaje-js';
                        msgError.innerHTML = mensaje;

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