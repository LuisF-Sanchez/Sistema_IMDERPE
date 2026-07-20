<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../controlador/controlador_ver_profesores.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Profesores - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style16.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
<a href="../vista/inicio.php" class="btn-back">
    <i class="fas fa-arrow-left"></i> Volver al Inicio
</a>
            <a href="registrar_profesor.php" class="btn-back">
                <i class="fas fa-user-plus"></i> Registrar Profesor
            </a>
        </div>
    </header>

    <main class="central-container">
        <h1 class="main-title">Gestión de Profesores</h1>
        
        <div class="glass-table-container">
            <?php if (isset($resultado) && $resultado->num_rows > 0): ?>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Profesor</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Instituto Educativo</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['cedula']); ?></strong></td>
                                <td>
                                    <span><?php echo htmlspecialchars($row['nombre']); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                                <td>
                                    <?php echo !empty($row['telefono']) ? htmlspecialchars($row['telefono']) : '<span class="text-por-asignar">No registrado</span>'; ?>
                                </td>
                                <td>
                                    <?php echo !empty($row['correo']) ? htmlspecialchars($row['correo']) : '<span class="text-por-asignar">No registrado</span>'; ?>
                                </td>
                                <td>
                                    <span class="badge-antiguedad">
                                        <?php echo htmlspecialchars($row['instituto_educativo']); ?>
                                    </span>
                                </td>
                                <td class="action-cell">
                                    <div class="action-buttons-wrapper">
                                        <a href="editar_profesor.php?id=<?php echo $row['id']; ?>" class="btn-table edit">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="color: white; text-align: center; padding: 40px;">
                    <i class="fas fa-info-circle fa-3x" style="margin-bottom: 15px; opacity: 0.5;"></i>
                    <p>No se encontraron profesores registrados en el sistema educativo.</p>
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
                text: 'El profesor ha sido guardado correctamente.',
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

        <?php if (isset($_GET['edit_exito'])): ?>
            Swal.fire({
                title: '¡Cambios Guardados!',
                text: 'La información del profesor se ha actualizado correctamente.',
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
                title: '¡Profesor ya existe!',
                text: 'La cédula ingresada ya pertenece a otro profesor registrado.',
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

        <?php if (isset($_GET['error'])): ?>
            Swal.fire({
                title: 'Error del Sistema',
                text: 'Ha ocurrido un error inesperado al procesar la solicitud.',
                icon: 'error',
                timer: 3500,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                background: '#1D3D81',
                color: '#ffffff',
                iconColor: '#ff4d4d'
            });
            limpiarURL();
        <?php endif; ?>
    </script>
</body>
</html>
<?php 
$conexion->close(); 
?>