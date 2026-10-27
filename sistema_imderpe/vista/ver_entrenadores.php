<?php
session_start();

require_once '../controlador/controlador_entrenadores.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Entrenadores - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style11.css">
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
                        <a href="ver_entrenadores.php?pagina=<?php echo $pagina - 1; ?>" class="num-box nav-arrow" title="Página Anterior">
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
                        <a href="ver_entrenadores.php?pagina=<?php echo $i; ?>" class="num-box <?php echo ($i == $pagina) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <!-- Flecha Siguiente -->
                    <?php if ($pagina < $total_paginas): ?>
                        <a href="ver_entrenadores.php?pagina=<?php echo $pagina + 1; ?>" class="num-box nav-arrow" title="Página Siguiente">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <a href="registrar_entrenadores.php" class="btn-back">
                <i class="fas fa-user-tie"></i> Registrar Entrenador
            </a>
        </div>
    </header>

    <main class="central-container">
        <h1 class="main-title">Panel de Entrenadores</h1>
        
        <div class="glass-table-container">
            <?php if (isset($resultado) && $resultado->num_rows > 0): ?>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Entrenador</th>
                            <th>Especialidad</th>
                            <th>Teléfono</th>
                            <th>Correo</th> 
                            <th>Estado</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['cedula']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre'] . " " . $row['apellido']); ?></td>
                                <td>
                                    <span class="badge-disciplina">
                                        <?php echo htmlspecialchars($row['nombre_disciplina'] ?? 'Sin asignar'); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($row['correo']); ?></td>
                                <td>
                                    <?php $clase = ($row['estado'] == 'activo') ? 'estado-activo' : 'estado-inactivo'; ?>
                                    <span class="badge-estado <?php echo $clase; ?>"><?php echo ucfirst($row['estado']); ?></span>
                                </td>
                                <td class="action-cell">
                                    <a href="editar_entrenador.php?id=<?php echo $row['id']; ?>" class="btn-table edit">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    
                                    <?php if ($row['estado'] == 'activo'): ?>
                                        <a href="../controlador/controlador_estado_entrenador.php?id=<?php echo $row['id']; ?>&actual=activo" class="btn-inactivar">
                                            <i class="fas fa-user-slash"></i> Inactivar
                                        </a>
                                    <?php else: ?>
                                        <a href="../controlador/controlador_estado_entrenador.php?id=<?php echo $row['id']; ?>&actual=inactivo" class="btn-activar">
                                            <i class="fas fa-user-check"></i> Activar
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state" style="text-align: center; color: white; padding: 40px;">
                    <i class="fas fa-user-tie fa-3x" style="margin-bottom: 15px; opacity: 0.5;"></i>
                    <p>No hay entrenadores registrados actualmente.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function limpiarURL() { 
        window.history.replaceState({}, document.title, window.location.pathname); 
    }

    <?php 
    $mensajes = [
        'registro_exito' => ['¡Registro Exitoso!', 'El entrenador ha sido incorporado al sistema.'],
        'edit_exito' => ['Actualizado', 'Datos del entrenador actualizados correctamente.'],
        'estado_exito' => ['Estado Cambiado', 'El estado del entrenador se actualizó correctamente.'],
        'error' => ['Error', 'No se pudo realizar la operación solicitada.'],
        'error_duplicado' => ['Cédula Duplicada', 'Este entrenador ya se encuentra registrado en el sistema.'],
        'error_campos' => ['Atención', 'Todos los campos obligatorios deben ser completados.']
    ];

    foreach ($mensajes as $get => $texto) { 
        if (isset($_GET[$get])) { ?>
            Swal.fire({
                title: '<?php echo $texto[0]; ?>',
                text: '<?php echo $texto[1]; ?>',
                icon: '<?php echo ($get == "error" || $get == "error_duplicado") ? "error" : "success"; ?>',
                background: '#1D3D81',
                color: '#fff',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            });
            limpiarURL();
    <?php 
        } 
    } 
    ?>
</script>
</body>
</html>