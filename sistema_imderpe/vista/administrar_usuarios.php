<?php
session_start();
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
                            <th style="text-align: center;">Acciones</th> </tr>
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
                                        <span class="badge badge-admin">
                                            <i class="fas fa-crown"></i> Admin
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-user">
                                            <i class="fas fa-user"></i> Usuario
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="action-cell">
                                    <a href="#" class="btn-table edit">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="#" class="btn-table delete">
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

    <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
        <?php 
            $tipoMsg = ($_GET['tipo'] == 'administrador') ? 'Administrador registrado' : 'Usuario registrado';
        ?>
        <script>
            Swal.fire({
                title: '¡Éxito!',
                text: '<?php echo $tipoMsg; ?> correctamente.',
                icon: 'success',
                timer: 3500,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                background: '#1D3D81',
                color: '#ffffff',
                iconColor: '#28a745'
            });
            window.history.replaceState({}, document.title, window.location.pathname);
        </script>
    <?php endif; ?>

</body>
</html>
<?php 
$conexion->close(); 
?>