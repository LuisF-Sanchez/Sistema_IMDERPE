<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../controlador/conexion.php';

$res_empleados = $conexion->query("SELECT id, nombre, apellido, cargo FROM empleados WHERE estado = 'activo' ORDER BY nombre ASC");
$res_tipos = $conexion->query("SELECT id, nombre_tipo FROM tipos_actividad ORDER BY nombre_tipo ASC");

$empleados_array = [];
if ($res_empleados && $res_empleados->num_rows > 0) {
    while($e = $res_empleados->fetch_assoc()) {
        $empleados_array[] = $e;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Actividad - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style14.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form action="../controlador/controlador_registrar_actividad.php" method="POST" enctype="multipart/form-data" class="glass-form">
            <div class="logo-container">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-form">
            </div>
            <h2 class="form-title">Registro de Actividad</h2>

            <div class="form-grid">
                
                <div class="input-group">
                    <i class="fas fa-running"></i>
                    <input type="text" name="nombre_actividad" placeholder="Nombre de la Actividad" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-calendar-alt"></i>
                    <input type="date" name="fecha" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="lugar" placeholder="Lugar o Instalación" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-layer-group"></i>
                    <select name="tipo_id" id="tipo_id" required>
                        <option value="" disabled selected>Seleccione Tipo de Actividad</option>
                        <?php if($res_tipos && $res_tipos->num_rows > 0): ?>
                            <?php foreach($res_tipos as $t): ?>
                                <option value="<?php echo $t['id']; ?>">
                                    <?php echo htmlspecialchars($t['nombre_tipo']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="1">Recreativa</option>
                            <option value="2">Deportiva</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="input-group full-width">
                    <i class="fas fa-book-open" style="top: 22px;"></i>
                    <textarea name="resena" placeholder="Escriba una breve reseña histórica de la actividad..." rows="3"></textarea>
                </div>

                <div class="responsables-section full-width">
                    <label class="section-label"><i class="fas fa-users"></i> Asignación de Responsables</label>
                    <div id="contenedor-responsables">
                        <div class="responsable-row">
                            <div class="input-group field-dinamico">
                                <i class="fas fa-user-shield"></i>
                                <select name="empleado_id[]" required disabled class="select-responsable">
                                    <option value="" disabled selected>Primero seleccione una actividad...</option>
                                    <?php foreach($empleados_array as $e): ?>
                                        <option value="<?php echo $e['id']; ?>">
                                            <?php echo htmlspecialchars($e['nombre'] . " " . $e['apellido'] . " - " . $e['cargo']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="button" class="btn-dinamico add" id="btn-add-responsable" disabled title="Añadir Responsable">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="multimedia-section full-width">
                    <div class="file-group">
                        <label class="file-label" for="foto_actividad">
                            <i class="fas fa-camera"></i> Subir Foto de la Actividad
                        </label>
                        <input type="file" id="foto_actividad" name="foto_actividad" accept="image/*">
                    </div>
                    <div class="preview-container" id="preview-box" style="display: none;">
                        <img id="img-preview" src="" alt="Vista previa de la actividad">
                    </div>
                </div>

            </div>

            <div class="action-row">
                <button type="submit" name="btn_registrar" value="ok" class="btn-register">Registrar Actividad</button>
                <a href="inicio.php" class="btn-cancel">Cancelar y Volver</a>
            </div>
        </form>
    </div>

    <script>
        const listaEmpleados = <?php echo json_encode($empleados_array); ?>;
        const tipoActividadSelect = document.getElementById('tipo_id');
        const btnAddResponsable = document.getElementById('btn-add-responsable');
        const contenedorResponsables = document.getElementById('contenedor-responsables');

        tipoActividadSelect.addEventListener('change', function() {
            const selects = document.querySelectorAll('.select-responsable');
            if (this.value !== "") {
                btnAddResponsable.disabled = false;
                selects.forEach(select => {
                    select.disabled = false;
                    if (select.querySelector('option[value=""]')) {
                        select.querySelector('option[value=""]').textContent = "Seleccione Responsable";
                    }
                });
            } else {
                btnAddResponsable.disabled = true;
                contenedorResponsables.innerHTML = `
                    <div class="responsable-row">
                        <div class="input-group field-dinamico">
                            <i class="fas fa-user-shield"></i>
                            <select name="empleado_id[]" required disabled class="select-responsable">
                                <option value="" disabled selected>Primero seleccione una actividad...</option>
                            </select>
                        </div>
                        <button type="button" class="btn-dinamico add" id="btn-add-responsable" disabled><i class="fas fa-plus"></i></button>
                    </div>`;
            }
        });

        contenedorResponsables.addEventListener('click', function(e) {
            if (e.target.closest('#btn-add-responsable')) {
                const nuevaFila = document.createElement('div');
                nuevaFila.classList.add('responsable-row', 'animated-row');

                let opciones = '<option value="" disabled selected>Seleccione Responsable</option>';
                listaEmpleados.forEach(e => {
                    opciones += `<option value="${e.id}">${e.nombre} ${e.apellido} - ${e.cargo}</option>`;
                });

                nuevaFila.innerHTML = `
                    <div class="input-group field-dinamico">
                        <i class="fas fa-user-shield"></i>
                        <select name="empleado_id[]" required class="select-responsable">
                            ${opciones}
                        </select>
                    </div>
                    <button type="button" class="btn-dinamico remove" title="Quitar Responsable">
                        <i class="fas fa-minus"></i>
                    </button>
                `;
                contenedorResponsables.appendChild(nuevaFila);
            }

            if (e.target.closest('.btn-dinamico.remove')) {
                const fila = e.target.closest('.responsable-row');
                fila.remove();
            }
        });

        document.getElementById('foto_actividad').addEventListener('change', function(e) {
            const previewBox = document.getElementById('preview-box');
            const imgPreview = document.getElementById('img-preview');
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imgPreview.src = event.target.result;
                    previewBox.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                previewBox.style.display = 'none';
            }
        });
    </script>
</body>
</html>