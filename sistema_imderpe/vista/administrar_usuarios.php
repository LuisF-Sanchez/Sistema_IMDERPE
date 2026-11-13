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
    <link rel="stylesheet" href="../estilo/style5.css"> <!-- Para asegurar estilos de paginación glassmorphism si aplica -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <a href="../vista/inicio.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver al Inicio
            </a>

            <!-- APARTADO DE NÚMEROS / PAGINACIÓN EN EL ENCABEZADO -->
            <?php if (isset($total_paginas) && $total_paginas > 1): ?>
                <div class="paginacion-header" style="display: flex; align-items: center; gap: 5px; flex-wrap: wrap;">
                    <!-- Flecha Anterior -->
                    <?php if ($pagina > 1): ?>
                        <a href="administrar_usuarios.php?pagina=<?php echo $pagina - 1; ?>" class="num-box nav-arrow" title="Sección Anterior">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>

                    <!-- Rango dinámico de hasta 5 números visibles -->
                    <?php 
                        $max_visibles = 5;
                        $inicio_num = max(1, $pagina - floor($max_visibles / 2));
                        $fin_num = min($total_paginas, $inicio_num + $max_visibles - 1);

                        if (($fin_num - $inicio_num + 1) < $max_visibles) {
                            $inicio_num = max(1, $fin_num - $max_visibles + 1);
                        }

                        for ($i = $inicio_num; $i <= $fin_num; $i++): 
                    ?>
                        <a href="administrar_usuarios.php?pagina=<?php echo $i; ?>" class="num-box <?php echo ($i == $pagina) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <!-- Flecha Siguiente -->
                    <?php if ($pagina < $total_paginas): ?>
                        <a href="administrar_usuarios.php?pagina=<?php echo $pagina + 1; ?>" class="num-box nav-arrow" title="Sección Siguiente">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>

                    <!-- Buscador si hay más de 5 secciones -->
                    <?php if ($total_paginas > 5): ?>
                        <form action="administrar_usuarios.php" method="GET" style="display: inline-flex; align-items: center; margin-left: 8px;">
                            <input type="number" name="pagina" min="1" max="<?php echo $total_paginas; ?>" placeholder="Sec..." required style="width: 70px; padding: 4px; border-radius: 4px; border: 1px solid #ccc; text-align: center; font-size: 14px;">
                            <button type="submit" class="num-box" style="padding: 4px 8px; margin-left: 4px; cursor: pointer; border: none; background: #1D3D81; color: white; border-radius: 4px;"><i class="fas fa-search"></i></button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

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
                                    <!-- Pasamos la sección actual en el enlace -->
                                    <a href="editar_usuario.php?id=<?php echo $row['id']; ?>&pagina=<?php echo $pagina; ?>" class="btn-table edit">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    
                                    <a href="javascript:void(0);" onclick="advertenciaEliminar(<?php echo $row['id']; ?>, <?php echo $pagina; ?>)" class="btn-table delete">
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

        function advertenciaEliminar(id, pagina) {
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
                    window.location.href = "../controlador/controlador_eliminar_usuario.php?id=" + id + "&pagina=" + pagina;
                }
            });
        }

        <?php if (isset($_GET['edit_exito'])): ?>
            Swal.fire({ title: '¡Cambios Guardados!', text: 'La información del usuario ha sido actualizada correctamente.', icon: 'success', timer: 3000, toast: true, position: 'top-end', showConfirmButton: false, background: '#1D3D81', color: '#ffffff', iconColor: '#28a745' });
            limpiarURL();
        <?php endif; ?>

        <?php if (isset($_GET['eliminar_exito'])): ?>
            Swal.fire({ title: '¡Eliminado!', text: 'El registro ha sido removido correctamente del sistema.', icon: 'success', timer: 3000, toast: true, position: 'top-end', showConfirmButton: false, background: '#1D3D81', color: '#ffffff', iconColor: '#28a745' });
            limpiarURL();
        <?php endif; ?>
    </script>
</body>
</html>
<?php 
if (isset($conexion) && $conexion instanceof mysqli) {
    $conexion->close();
}
?>