<?php
session_start();

if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}

include "../controlador/tabla_usuarios.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Usuarios - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <a href="../vista/inicio.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver al Inicio
            </a>
            <a href="../vista/registrar.php" class="btn-back">
                <i class="fas fa-user-plus"></i> Registrar Usuario
            </a>
        </div>
    </header>

    <main class="central-container">
        <h1 class="main-title">Gestión de Usuarios</h1>
        
        <div class="glass-table-container">
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cédula</th>
                            <th>Teléfono</th>
                            <th>Correo</th>
                            <th>Tipo</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['cedula']); ?></td>
                                <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($row['correo']); ?></td>
                                <td>
                                    <?php if ($row['tipo'] == 'administrador'): ?>
                                        <span class="badge badge-admin"><i class="fas fa-crown"></i> Admin</span>
                                    <?php else: ?>
                                        <span class="badge badge-user"><i class="fas fa-user"></i> Usuario</span>
                                    <?php endif; ?>
                                </td>
                                <td class="action-cell">
                                    <a href="editar_usuario.php?id=<?php echo $row['id']; ?>" class="btn-table edit">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    
                                    <a href="javascript:void(0);" onclick="advertenciaEliminar(<?php echo $row['id']; ?>)" class="btn-table delete">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="color: white; text-align: center; padding: 20px;">
                    <i class="fas fa-info-circle"></i> No se encontraron usuarios registrados.
                </p>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function limpiarURL() {
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        function advertenciaEliminar(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción eliminará permanentemente al usuario del sistema.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff4d4d',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                background: '#1D3D81',
                color: '#ffffff',
                iconColor: '#FBC02D'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../controlador/controlador_eliminar_usuario.php?id=" + id;
                }
            });
        }

        <?php if (isset($_GET['edit_exito'])): ?>
            Swal.fire({
                title: '¡Cambios Guardados!',
                text: 'La información del usuario ha sido actualizada correctamente.',
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

        <?php if (isset($_GET['eliminar_exito'])): ?>
            Swal.fire({
                title: '¡Eliminado!',
                text: 'El registro ha sido removido correctamente del sistema.',
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

        <?php if (isset($_GET['error_duplicado'])): ?>
            Swal.fire({
                title: '¡Cédula Registrada!',
                text: 'La cédula ingresada ya pertenece a otro usuario.',
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
                title: '¡Error!',
                text: 'Sucedió un error interno en el servidor.',
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