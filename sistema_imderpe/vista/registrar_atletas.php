<?php
session_start();
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
    <header class="main-header">
        <div class="header-container">
            <a href="ver_atletas.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver a la Lista
            </a>
        </div>
    </header>

    <main class="central-container">
        <div class="glass-form-container">
            <div class="form-header">
                <i class="fas fa-running fa-2x"></i>
                <h2>Registro de Atleta</h2>
            </div>

            <form action="../controlador/controlador_registrar_atleta.php" method="POST" class="main-form">
                <div class="form-grid">
                    <div class="input-group">
                        <label><i class="fas fa-id-card"></i> Cédula</label>
                        <input type="text" name="cedula" placeholder="Ej: 25123456" required>
                    </div>

                    <div class="input-group">
                        <label><i class="fas fa-user"></i> Nombre</label>
                        <input type="text" name="nombre" required>
                    </div>

                    <div class="input-group">
                        <label><i class="fas fa-user"></i> Apellido</label>
                        <input type="text" name="apellido" required>
                    </div>

                    <div class="input-group">
                        <label><i class="fas fa-calendar-alt"></i> Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" required>
                    </div>

                    <div class="input-group">
                        <label><i class="fas fa-venus-mars"></i> Género</label>
                        <select name="genero" required>
                            <option value="">Seleccione...</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>

                    <!-- NUEVO CAMPO: COMUNA -->
                    <div class="input-group">
                        <label><i class="fas fa-map-marker-alt"></i> Comuna</label>
                        <input type="text" name="comuna" placeholder="Ej: Comuna La Guadalupe" required>
                    </div>

                    <!-- NUEVO CAMPO: CATEGORÍA -->
                    <div class="input-group">
                        <label><i class="fas fa-layer-group"></i> Categoría</label>
                        <select name="categoria" required>
                            <option value="">Seleccione...</option>
                            <option value="Infantil">Infantil</option>
                            <option value="Juvenil">Juvenil</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label><i class="fas fa-trophy"></i> Disciplina</label>
                        <select name="disciplina_id" required>
                            <option value="">Seleccione disciplina...</option>
                            <?php while($d = $res_disciplinas->fetch_assoc()): ?>
                                <option value="<?php echo $d['id']; ?>"><?php echo $d['nombre_disciplina']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label><i class="fas fa-user-tie"></i> Entrenador</label>
                        <select name="entrenador_id" required>
                            <option value="">Seleccione entrenador...</option>
                            <?php while($e = $res_entrenadores->fetch_assoc()): ?>
                                <option value="<?php echo $e['id']; ?>"><?php echo $e['nombre'] . " " . $e['apellido']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <label><i class="fas fa-users"></i> Representante</label>
                        <div class="select-with-btn">
                            <select name="representante_id" id="select-representante" required>
                                <option value="">Seleccione...</option>
                                <?php $res_representantes->data_seek(0); while($r = $res_representantes->fetch_assoc()): ?>
                                    <option value="<?php echo $r['id']; ?>">
                                        <?php echo $r['cedula'] . " - " . $r['nombre']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <button type="button" class="btn-add-fast" onclick="abrirModal()" title="Nuevo Representante">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" name="btn_registrar" value="ok" class="btn-submit">Registrar Atleta</button>
                </div>
            </form>
        </div>
    </main>

    <div id="modalRepresentante" class="modal">
        <div class="modal-bubble">
            <span class="close-btn" onclick="cerrarModal()">&times;</span>
            <div class="form-header">
                <i class="fas fa-user-shield fa-2x"></i>
                <h3>Nuevo Representante</h3>
            </div>
            
            <form id="form-representante-rapido">
                <div class="modal-grid">
                    <div class="input-group">
                        <label>Cédula</label>
                        <input type="text" id="rep_cedula" required>
                    </div>
                    <div class="input-group">
                        <label>Nombre</label>
                        <input type="text" id="rep_nombre" required>
                    </div>
                    <div class="input-group">
                        <label>Apellido</label>
                        <input type="text" id="rep_apellido" required>
                    </div>
                    <div class="input-group">
                        <label>Teléfono</label>
                        <input type="text" id="rep_telefono" placeholder="Ej: 0412-1234567" required>
                    </div>
                    <div class="input-group full-width">
                        <label>Correo Electrónico</label>
                        <input type="email" id="rep_correo" placeholder="correo@ejemplo.com">
                    </div>
                    <div class="input-group full-width">
                        <label>Dirección de Habitación</label>
                        <textarea id="rep_direccion" rows="2" placeholder="Especifique calle, sector..."></textarea>
                    </div>
                </div>
                <button type="button" onclick="guardarRepresentante()" class="btn-submit">
                    <i class="fas fa-save"></i> Guardar y Seleccionar
                </button>
            </form>
        </div>
    </div>

    <script>
        function abrirModal() {
            document.getElementById('modalRepresentante').style.display = 'flex';
        }

        function cerrarModal() {
            document.getElementById('modalRepresentante').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('modalRepresentante');
            if (event.target == modal) { cerrarModal(); }
        }

        function guardarRepresentante() {
            const datos = new FormData();
            datos.append('cedula', document.getElementById('rep_cedula').value);
            datos.append('nombre', document.getElementById('rep_nombre').value);
            datos.append('apellido', document.getElementById('rep_apellido').value);
            datos.append('telefono', document.getElementById('rep_telefono').value);
            datos.append('correo', document.getElementById('rep_correo').value);
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
                    alert("Error: " + (data.error || "No se pudo registrar"));
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>