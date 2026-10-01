<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../controlador/conexion.php';

$res_representantes = $conexion->query("SELECT id, nombre, apellido, cedula FROM representantes ORDER BY nombre ASC");
$res_entrenadores = $conexion->query("SELECT id, nombre, apellido FROM entrenadores WHERE estado = 'activo' ORDER BY nombre ASC");
$res_disciplinas = $conexion->query("SELECT id, nombre_disciplina FROM disciplinas ORDER BY nombre_disciplina ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Atleta - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style9.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form id="form-atleta" action="../controlador/controlador_registrar_atleta.php" method="POST" class="glass-form" novalidate>
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">Registro de Atleta</h2>

            <div class="form-grid">
                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" id="cedula" name="cedula" placeholder="Cédula de Identidad">
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre">
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="apellido" name="apellido" placeholder="Apellido">
                </div>

                <div class="input-group">
                    <i class="fas fa-calendar-alt"></i>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" title="Fecha de Nacimiento">
                </div>

                <div class="input-group">
                    <i class="fas fa-venus-mars"></i>
                    <select id="genero" name="genero">
                        <option value="" disabled selected>Seleccione Género</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </div>

                <div class="input-group">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" id="comuna" name="comuna" placeholder="Comuna">
                </div>

                <div class="input-group">
                    <i class="fas fa-layer-group"></i>
                    <select id="categoria" name="categoria">
                        <option value="" disabled selected>Seleccione Categoría</option>
                        <option value="Infantil">Infantil</option>
                        <option value="Juvenil">Juvenil</option>
                    </select>
                </div>

                <div class="input-group">
                    <i class="fas fa-trophy"></i>
                    <select id="disciplina_id" name="disciplina_id">
                        <option value="" disabled selected>Seleccione Disciplina</option>
                        <?php while($d = $res_disciplinas->fetch_assoc()): ?>
                            <option value="<?php echo $d['id']; ?>"><?php echo htmlspecialchars($d['nombre_disciplina']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="input-group">
                    <i class="fas fa-user-tie"></i>
                    <select id="entrenador_id" name="entrenador_id">
                        <option value="" disabled selected>Seleccione Entrenador</option>
                        <?php while($e = $res_entrenadores->fetch_assoc()): ?>
                            <option value="<?php echo $e['id']; ?>"><?php echo htmlspecialchars($e['nombre'] . " " . $e['apellido']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="input-group">
                    <i class="fas fa-users"></i>
                    <div class="select-with-btn">
                        <select name="representante_id" id="select-representante">
                            <option value="" disabled selected>Seleccione Representante</option>
                            <?php $res_representantes->data_seek(0); while($r = $res_representantes->fetch_assoc()): ?>
                                <option value="<?php echo $r['id']; ?>">
                                    <?php echo htmlspecialchars($r['cedula'] . " - " . $r['nombre'] . " " . $r['apellido']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <button type="button" class="btn-add-fast" onclick="abrirModal()" title="Nuevo Representante">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="action-row">
                <?php if (isset($_GET['error']) && $_GET['error'] == 'cedula_existe'): ?>
                    <div class="alert-error-box">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>La cédula ingresada ya se encuentra registrada en el sistema.</span>
                    </div>
                <?php endif; ?>

                <button type="submit" name="btn_registrar" value="ok" class="btn-register">Registrar Atleta</button>
                <a href="ver_atletas.php" class="btn-cancel">Cancelar y Volver</a>
            </div>
        </form>
    </div>

    <!-- MODAL DE REPRESENTANTE RÁPIDO -->
    <div id="modalRepresentante" class="modal">
        <div class="modal-bubble">
            <span class="close-btn" onclick="cerrarModal()">&times;</span>
            <div class="form-header">
                <i class="fas fa-user-shield fa-2x"></i>
                <h3>Nuevo Representante</h3>
            </div>
            
            <form id="form-representante-rapido" novalidate>
                <div class="modal-grid">
                    <div class="input-group">
                        <i class="fas fa-id-card"></i>
                        <input type="text" id="rep_cedula" placeholder="Cédula">
                    </div>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="rep_nombre" placeholder="Nombre">
                    </div>
                    <div class="input-group">
                        <i class="fas fa-user"></i>
                        <input type="text" id="rep_apellido" placeholder="Apellido">
                    </div>
                    <div class="input-group">
                        <i class="fas fa-phone"></i>
                        <input type="text" id="rep_telefono" placeholder="Número de Teléfono">
                    </div>
                    <div class="input-group full-width">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="rep_correo" placeholder="Correo Electrónico">
                    </div>
                    <div class="input-group full-width textarea-group">
                        <i class="fas fa-map-marker-alt"></i>
                        <textarea id="rep_direccion" rows="2" placeholder="Dirección de Habitación (Calle, Sector...)"></textarea>
                    </div>
                </div>

                <button type="button" onclick="guardarRepresentante()" class="btn-register modal-submit-btn">
                    <i class="fas fa-save"></i> Guardar y Seleccionar
                </button>
            </form>
        </div>
    </div>

    <script>
        const formAtleta = document.getElementById('form-atleta');

        function ocultarErroresGrupo(parentGroup) {
            parentGroup.querySelectorAll('.error-mensaje').forEach(el => el.remove());
            const input = parentGroup.querySelector('input, select, textarea');
            if (input) input.classList.remove('input-error');
        }

        document.addEventListener('input', function(e) {
            const parent = e.target.closest('.input-group');
            if (parent) ocultarErroresGrupo(parent);
        });

        document.addEventListener('change', function(e) {
            const parent = e.target.closest('.input-group');
            if (parent) ocultarErroresGrupo(parent);
        });

        function mostrarErrorCampo(campo, mensaje) {
            campo.classList.add('input-error');
            const parentGroup = campo.closest('.input-group');
            if (parentGroup && !parentGroup.querySelector('.error-mensaje')) {
                const msgError = document.createElement('span');
                msgError.className = 'error-mensaje';
                msgError.innerHTML = mensaje;
                parentGroup.appendChild(msgError);
            }
        }

        formAtleta.addEventListener('submit', function(e) {
            formAtleta.querySelectorAll('.error-mensaje').forEach(el => el.remove());
            formAtleta.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

            let hayError = false;

            const campos = [
                { id: 'cedula', msg: '* Debe colocar la cédula' },
                { id: 'nombre', msg: '* Debe colocar el nombre' },
                { id: 'apellido', msg: '* Debe colocar el apellido' },
                { id: 'fecha_nacimiento', msg: '* Debe seleccionar la fecha de nacimiento' },
                { id: 'genero', msg: '* Debe seleccionar el género' },
                { id: 'comuna', msg: '* Debe colocar la comuna' },
                { id: 'categoria', msg: '* Debe seleccionar la categoría' },
                { id: 'disciplina_id', msg: '* Debe seleccionar una disciplina' },
                { id: 'entrenador_id', msg: '* Debe seleccionar un entrenador' },
                { id: 'select-representante', msg: '* Debe seleccionar un representante' }
            ];

            campos.forEach(campo => {
                const elem = document.getElementById(campo.id);
                if (elem && (!elem.value || elem.value.trim() === "")) {
                    hayError = true;
                    mostrarErrorCampo(elem, campo.msg);
                }
            });

            if (hayError) {
                e.preventDefault();
            }
        });

        function abrirModal() {
            document.getElementById('modalRepresentante').style.display = 'flex';
        }

        function cerrarModal() {
            const modal = document.getElementById('modalRepresentante');
            modal.style.display = 'none';
            modal.querySelectorAll('.error-mensaje').forEach(el => el.remove());
            modal.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
        }

        window.onclick = function(event) {
            const modal = document.getElementById('modalRepresentante');
            if (event.target == modal) { cerrarModal(); }
        }

        function guardarRepresentante() {
            const modal = document.getElementById('modalRepresentante');

            // Limpia mensajes y estilos previos
            modal.querySelectorAll('.error-mensaje').forEach(el => el.remove());
            modal.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

            let hayErrorModal = false;

            const repCedula = document.getElementById('rep_cedula');
            const repNombre = document.getElementById('rep_nombre');
            const repApellido = document.getElementById('rep_apellido');
            const repTelefono = document.getElementById('rep_telefono');
            const repCorreo = document.getElementById('rep_correo');

            // Validaciones locales básicas
            if (!repCedula.value.trim()) { mostrarErrorCampo(repCedula, '* Requerido'); hayErrorModal = true; }
            if (!repNombre.value.trim()) { mostrarErrorCampo(repNombre, '* Requerido'); hayErrorModal = true; }
            if (!repApellido.value.trim()) { mostrarErrorCampo(repApellido, '* Requerido'); hayErrorModal = true; }
            if (!repTelefono.value.trim()) { mostrarErrorCampo(repTelefono, '* Requerido'); hayErrorModal = true; }

            if (hayErrorModal) return;

            const datos = new FormData();
            datos.append('cedula', repCedula.value);
            datos.append('nombre', repNombre.value);
            datos.append('apellido', repApellido.value);
            datos.append('telefono', repTelefono.value);
            datos.append('correo', repCorreo.value);
            datos.append('direccion', document.getElementById('rep_direccion').value);

            fetch('../controlador/controlador_registrar_representante_fast.php', {
                method: 'POST',
                body: datos
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById('select-representante');
                    const nuevaOpcion = document.createElement('option');
                    nuevaOpcion.value = data.id; 
                    nuevaOpcion.text = data.cedula + " - " + data.nombre;
                    nuevaOpcion.selected = true;
                    select.add(nuevaOpcion);
                    
                    document.getElementById('form-representante-rapido').reset();
                    cerrarModal();
                } else {
                    // SI EL SERVIDOR DEVUELVE UN DICCIONARIO CON MÚLTIPLES ERRORES
                    if (data.errors) {
                        if (data.errors.cedula) mostrarErrorCampo(repCedula, '* ' + data.errors.cedula);
                        if (data.errors.telefono) mostrarErrorCampo(repTelefono, '* ' + data.errors.telefono);
                        if (data.errors.correo) mostrarErrorCampo(repCorreo, '* ' + data.errors.correo);
                    } else if (data.error) {
                        // REPALDO SI DEVUELVE UN SOLO TEXTO DE ERROR
                        const errorLower = data.error.toLowerCase();
                        if (errorLower.includes('cédula')) mostrarErrorCampo(repCedula, '* ' + data.error);
                        else if (errorLower.includes('teléfono')) mostrarErrorCampo(repTelefono, '* ' + data.error);
                        else if (errorLower.includes('correo')) mostrarErrorCampo(repCorreo, '* ' + data.error);
                        else alert(data.error);
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>