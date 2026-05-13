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
                            <th>ID</th>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['cedula']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['apellido']); ?></td>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function limpiarURL() {
            window.history.replaceState({}, document.title, window.location.pathname);
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
    </script>

</body>
</html>
<?php 
$conexion->close(); 
?>