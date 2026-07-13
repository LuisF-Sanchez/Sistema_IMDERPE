<?php
session_start();
require_once '../controlador/controlador_empleados.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <a href="../vista/inicio.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver al Inicio
            </a>
            <a href="registrar_empleado.php" class="btn-back">
                <i class="fas fa-user-plus"></i> Registrar Empleado
            </a>
        </div>
    </header>

    <main class="central-container">
        <h1 class="main-title">Gestión de Empleados</h1>
        
        <div class="glass-table-container">
            <?php if (isset($resultado) && $resultado->num_rows > 0): ?>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Empleado</th>
                            <th>Apellido</th>
                            <th>Cargo</th>
                            <th>Fecha de Ingreso</th>
                            <th>Años de Servicio</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['cedula']); ?></strong></td>
                                <td>
                                    <div class="col-empleado">
                                        <?php 
                                        if (!empty($row['foto']) && $row['foto'] !== 'defaultavatar.png' && file_exists("../fotos_empleados/" . $row['foto'])) {
                                            $ruta_foto = "../fotos_empleados/" . $row['foto'];
                                        } else {
                                            $ruta_foto = "../estilo/defaultavatar.png";
                                        }
                                        ?>
                                        <img src="<?php echo $ruta_foto; ?>?v=<?php echo time(); ?>" 
                                             alt="Foto" 
                                             class="avatar-tabla" 
                                             style="cursor: pointer;" 
                                             onclick="ampliarImagen('<?php echo $ruta_foto; ?>')">
                                        <span><?php echo htmlspecialchars($row['nombre']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                                <td>
                                    <span class="badge-cargo">
                                        <?php echo htmlspecialchars($row['cargo']); ?>
                                    </span>
                                </td>
                                <!-- Columna 1: Muestra solo la fecha limpia en formato día/mes/año -->
                                <td>
                                    <?php 
                                    if (!empty($row['fecha_ingreso'])) {
                                        $date = new DateTime($row['fecha_ingreso']);
                                        echo $date->format('j/n/Y'); // Formato ejemplo: 12/7/2025
                                    } else {
                                        echo '<span class="text-por-asignar">Por asignar</span>';
                                    }
                                    ?>
                                </td>
                                <!-- Columna 2: Calcula y muestra los años de servicio automáticamente -->
                                <td>
                                    <?php 
                                    if (!empty($row['fecha_ingreso'])) {
                                        $fecha_ingreso = new DateTime($row['fecha_ingreso']);
                                        $hoy = new DateTime();
                                        $diferencia = $hoy->diff($fecha_ingreso);
                                        $anios = $diferencia->y;

                                        if ($anios == 0) {
                                            echo '<span class="badge-antiguedad">Menos de 1 año</span>';
                                        } elseif ($anios == 1) {
                                            echo '<span class="badge-antiguedad">1 Año</span>';
                                        } else {
                                            echo '<span class="badge-antiguedad">' . $anios . ' Años</span>';
                                        }
                                    } else {
                                        echo '<span class="text-por-asignar">Por asignar</span>';
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($row['correo']); ?></td>
                                <td>
                                    <?php 
                                    $clase = ($row['estado'] == 'activo') ? 'estado-activo' : 'estado-inactivo'; 
                                    ?>
                                    <span class="badge-estado <?php echo $clase; ?>">
                                        <?php echo htmlspecialchars($row['estado']); ?>
                                    </span>
                                </td>
                                <td class="action-cell">
                                    <div class="action-buttons-wrapper">
                                        <a href="editar_empleado.php?id=<?php echo $row['id']; ?>" class="btn-table edit">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        
                                        <?php if ($row['estado'] == 'activo'): ?>
                                            <a href="../controlador/controlador_estado_empleado.php?id=<?php echo $row['id']; ?>&actual=activo" class="btn-inactivar">
                                                <i class="fas fa-user-slash"></i> Inactivar
                                            </a>
                                        <?php else: ?>
                                            <a href="../controlador/controlador_estado_empleado.php?id=<?php echo $row['id']; ?>&actual=inactivo" class="btn-activar">
                                                <i class="fas fa-user-check"></i> Activar
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="color: white; text-align: center; padding: 40px;">
                    <i class="fas fa-info-circle fa-3x" style="margin-bottom: 15px; opacity: 0.5;"></i>
                    <p>No se encontraron empleados registrados en el sistema.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <div id="imageModal" class="image-modal" onclick="cerrarImagen()">
        <img id="imgModalTarget" src="" alt="Foto ampliada">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function limpiarURL() {
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        function ampliarImagen(src) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('imgModalTarget');
            modalImg.src = src; 
            modal.classList.add('active'); 
        }

        function cerrarImagen() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('active'); 
        }

        <?php if (isset($_GET['registro']) && $_GET['registro'] == 'exito'): ?>
            Swal.fire({
                title: '¡Registro Exitoso!',
                text: 'El empleado ha sido guardado correctamente.',
                icon: 'success',
                timer: 3000,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                background: '#1D3D81',
                color: '#ffffff',
                iconColor: '#28a745'
            });
            limpiarURL();
        <?php endif; ?>

        <?php if (isset($_GET['cambio'])): 
            $nuevo_est = $_GET['cambio'];
            $texto = ($nuevo_est == 'activo') ? 'Empleado activado con éxito' : 'Empleado inactivado correctamente';
            $colorIcono = ($nuevo_est == 'activo') ? '#28a745' : '#dc3545';
        ?>
            Swal.fire({
                title: 'Estado Actualizado',
                text: '<?php echo $texto; ?>',
                icon: 'info',
                timer: 3000,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                background: '#1D3D81',
                color: '#ffffff',
                iconColor: '<?php echo $colorIcono; ?>'
            });
            limpiarURL();
        <?php endif; ?>

        <?php if (isset($_GET['edit_exito'])): ?>
            Swal.fire({
                title: '¡Cambios Guardados!',
                text: 'La información del empleado se ha actualizado correctamente.',
                icon: 'success',
                timer: 3000,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                background: '#1D3D81',
                color: '#ffffff',
                iconColor: '#4db8ff'
            });
            limpiarURL();
        <?php endif; ?>

        <?php if (isset($_GET['error_duplicado'])): ?>
            Swal.fire({
                title: '¡Usuario ya existe!',
                text: 'La cédula o el correo ingresados ya pertenecen a otro empleado registrado.',
                icon: 'warning',
                timer: 3500,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                background: '#1D3D81',
                color: '#ffffff',
                iconColor: '#FBC02D'
            });
            limpiarURL();
        <?php endif; ?>
    </script>
</body>
</html>
<?php 
$conexion->close(); 
?>