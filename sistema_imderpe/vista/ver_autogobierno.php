<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../controlador/controlador_autogobierno.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Autogobierno - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <a href="../vista/inicio.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver al Inicio
            </a>
            <a href="registrar_autogobierno.php" class="btn-back">
                <i class="fas fa-user-plus"></i> Registrar Responsable
            </a>
        </div>
    </header>

    <main class="central-container">
        <h1 class="main-title">Gestión de Autogobierno</h1>
        
        <div class="glass-table-container">
            <?php if (isset($resultado) && $resultado->num_rows > 0): ?>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Dirección</th>
                            <th>Comuna</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($row['cedula']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($row['correo']); ?></td>
                                <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                                <td>
                                    <span class="badge-antiguedad">
                                        <?php echo htmlspecialchars($row['comuna']); ?>
                                    </span>
                                </td>
                                <td class="action-cell">
                                    <div class="action-buttons-wrapper">
                                        <a href="editar_autogobierno.php?id=<?php echo $row['id']; ?>" class="btn-table edit">
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
                    <i class="fas fa-info-circle fa-3x" style="margin-bottom: 15px; opacity: 0.5; color: #FBC02D;"></i>
                    <p>No se encontraron responsables de autogobierno registrados en el sistema.</p>
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
                text: 'El responsable de autogobierno ha sido guardado correctamente.',
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
                text: 'La información del responsable de autogobierno ha sido actualizada.',
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
                title: '¡Cédula Duplicada!',
                text: 'La cédula ingresada ya pertenece a un responsable registrado.',
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

        <?php if (isset($_GET['error_campos'])): ?>
            Swal.fire({
                title: 'Campos Incompletos',
                text: 'Por favor, rellene todos los campos obligatorios.',
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

        <?php if (isset($_GET['error_db'])): ?>
            Swal.fire({
                title: 'Error del Sistema',
                text: 'No se pudo procesar la solicitud. Intente nuevamente.',
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