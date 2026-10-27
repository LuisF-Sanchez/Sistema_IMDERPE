<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Constancia de Participación - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style14.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <form id="form-constancia" action="../controlador/controlador_registrar_constancia.php" method="POST" target="_blank" class="glass-form" novalidate>
            <h2 class="form-title">Emitir Constancia de Participación</h2>

            <div class="form-grid">
                
                <!-- Nombres y Apellidos Libres -->
                <div class="input-group full-width">
                    <i class="fas fa-user"></i>
                    <input type="text" id="nombre_participante" name="nombre_participante" placeholder="Nombres y Apellidos del Participante">
                </div>

                <!-- Cédula (Obligatoria) -->
                <div class="input-group full-width">
                    <i class="fas fa-id-card"></i>
                    <input type="text" id="cedula" name="cedula" placeholder="Cédula de Identidad">
                </div>

                <!-- Fecha de Emisión -->
                <div class="input-group full-width">
                    <i class="fas fa-calendar-alt"></i>
                    <input type="date" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>">
                </div>

            </div>

            <div class="action-row">
                <button type="submit" name="btn_generar" value="ok" class="btn-register">
                    <i class="fas fa-file-pdf"></i> Generar Constancia PDF
                </button>
                <a href="inicio.php" class="btn-cancel">Cancelar y Volver</a>
            </div>
        </form>
    </div>

    <script>
        const formConstancia = document.getElementById('form-constancia');

        function ocultarErroresGrupo(parentGroup) {
            parentGroup.querySelectorAll('.error-mensaje').forEach(el => el.remove());
            const input = parentGroup.querySelector('input');
            if (input) input.classList.remove('input-error');
        }

        document.addEventListener('input', function(e) {
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

        formConstancia.addEventListener('submit', function(e) {
            formConstancia.querySelectorAll('.error-mensaje').forEach(el => el.remove());
            formConstancia.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));

            let hayError = false;

            // Validar Nombre
            const inputNombre = document.getElementById('nombre_participante');
            if (!inputNombre.value || inputNombre.value.trim() === "") {
                hayError = true;
                mostrarErrorCampo(inputNombre, "* Debe ingresar los nombres y apellidos");
            }

            // Validar Cédula Obligatoria
            const inputCedula = document.getElementById('cedula');
            if (!inputCedula.value || inputCedula.value.trim() === "") {
                hayError = true;
                mostrarErrorCampo(inputCedula, "* Debe ingresar la cédula de identidad");
            }

            // Validar Fecha
            const fecha = document.getElementById('fecha');
            if (!fecha.value || fecha.value.trim() === "") {
                hayError = true;
                mostrarErrorCampo(fecha, "* Debe seleccionar la fecha");
            }

            if (hayError) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>