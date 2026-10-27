<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../controlador/controlador_ver_representantes.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Representantes - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style16.css">
    <link rel="stylesheet" href="../estilo/style5.css"> <!-- Para heredar los estilos de paginación glassmorphism -->
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
                <div class="paginacion-header">
                    <!-- Flecha Anterior -->
                    <?php if ($pagina > 1): ?>
                        <a href="ver_representantes.php?pagina=<?php echo $pagina - 1; ?>" class="num-box nav-arrow" title="Página Anterior">
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
                        <a href="ver_representantes.php?pagina=<?php echo $i; ?>" class="num-box <?php echo ($i == $pagina) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <!-- Flecha Siguiente -->
                    <?php if ($pagina < $total_paginas): ?>
                        <a href="ver_representantes.php?pagina=<?php echo $pagina + 1; ?>" class="num-box nav-arrow" title="Página Siguiente">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Espaciador o botón adicional si hace falta -->
            <div></div>
        </div>
    </header>

    <main class="central-container">
        <h1 class="main-title">Gestión de Representantes</h1>
        
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
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['cedula']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($row['telefono'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($row['correo']); ?></td>
                                <td><?php echo htmlspecialchars($row['direccion']); ?></td>
                                <td class="action-cell">
                                    <a href="editar_representante.php?id=<?php echo $row['id']; ?>" class="btn-table edit">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div style="color: white; text-align: center; padding: 40px;">
                    <i class="fas fa-info-circle fa-3x" style="margin-bottom: 15px; opacity: 0.5;"></i>
                    <p>No se encontraron representantes registrados en el sistema.</p>
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
                text: 'El representante ha sido guardado correctamente.',
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
                text: 'La información del representante se ha actualizado correctamente.',
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
if (isset($conexion) && $conexion instanceof mysqli) {
    $conexion->close(); 
}
?>