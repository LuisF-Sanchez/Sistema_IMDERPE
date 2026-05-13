<?php
session_start();
require_once '../controlador/controlador_atletas.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Atletas - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style8.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <a href="../vista/inicio.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver al Inicio
            </a>
            <a href="registrar_atletas.php" class="btn-back">
                <i class="fas fa-running"></i> Registrar Atleta
            </a>
        </div>
    </header>

    <main class="central-container">
        <h1 class="main-title">Panel de Atletas</h1>
        
        <div class="glass-table-container">
            <?php if ($resultado && $resultado->num_rows > 0): ?>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cédula</th>
                            <th>Atleta</th>
                            <th>Nacimiento</th>
                            <th>Representante</th>
                            <th>Entrenador</th>
                            <th>Disciplina</th>
                            <th>Estado</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['cedula']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre'] . " " . $row['apellido']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['fecha_nacimiento'])); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_rep'] . " " . $row['apellido_rep']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_ent'] . " " . $row['apellido_ent']); ?></td>
                                <td><span class="badge-disciplina"><?php echo htmlspecialchars($row['disciplina']); ?></span></td>
                                <td>
                                    <?php $clase = ($row['estado'] == 'activo') ? 'estado-activo' : 'estado-inactivo'; ?>
                                    <span class="badge-estado <?php echo $clase; ?>"><?php echo ucfirst($row['estado']); ?></span>
                                </td>
                                <td class="action-cell">
                                    <a href="editar_atleta.php?id=<?php echo $row['id']; ?>" class="btn-table edit">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    
                                    <?php if ($row['estado'] == 'activo'): ?>
                                        <a href="../controlador/controlador_estado_atleta.php?id=<?php echo $row['id']; ?>&actual=activo" class="btn-inactivar">
                                            <i class="fas fa-user-slash"></i> Inactivar
                                        </a>
                                    <?php else: ?>
                                        <a href="../controlador/controlador_estado_atleta.php?id=<?php echo $row['id']; ?>&actual=inactivo" class="btn-activar">
                                            <i class="fas fa-user-check"></i> Activar
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-running fa-3x"></i>
                    <p>No hay atletas registrados actualmente.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function limpiarURL() { 
            window.history.replaceState({}, document.title, window.location.pathname); 
        }

        <?php if (isset($_GET['registro_exito'])): ?>
            Swal.fire({
                title: '¡Registro Exitoso!',
                text: 'El atleta ha sido incorporado al sistema correctamente.',
                icon: 'success',
                background: '#1D3D81',
                color: '#fff',
                confirmButtonColor: '#FBC02D'
            });
            limpiarURL();
        <?php endif; ?>

        <?php if (isset($_GET['edit_exito'])): ?>
            Swal.fire({
                title: 'Actualizado',
                text: 'Datos del atleta actualizados.',
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                background: '#1D3D81',
                color: '#fff'
            });
            limpiarURL();
        <?php endif; ?>

        <?php if (isset($_GET['estado_exito'])): ?>
            Swal.fire({
                title: 'Estado Cambiado',
                text: 'El estado del atleta se actualizó correctamente.',
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                background: '#28a745',
                color: '#fff'
            });
            limpiarURL();
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            Swal.fire({
                title: 'Error',
                text: 'No se pudo realizar la operación.',
                icon: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                background: '#dc3545',
                color: '#fff'
            });
            limpiarURL();
        <?php endif; ?>
    </script>
</body>
</html>