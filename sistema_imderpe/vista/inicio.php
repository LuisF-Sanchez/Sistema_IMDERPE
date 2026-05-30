<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<header class="main-header">
    <div class="header-container">
        <div class="welcome-text">
            <i class="fas fa-user-check"></i> 
            Bienvenido <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
        </div>        
        <nav class="nav-menu">
            <div class="dropdown">
                <button class="dropbtn">
                    <i class="fas fa-eye"></i> Ver 
                    <i class="fas fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="../vista/ver_empleados.php"><i class="fas fa-clipboard-list"></i> Empleados</a>
                    <a href="../vista/registrar_actividades.php"><i class="fas fa-calendar-alt"></i> Actividades</a>
                    <a href="../vista/ver_atletas.php"><i class="fas fa-medal"></i> Atletas</a>
                    <a href="../vista/ver_representantes.php"><i class="fas fa-users"></i> Rep. de Atletas</a>
                    <a href="../vista/ver_entrenadores.php"><i class="fa-solid fa-whistle"></i> Entrenadores</a>
                    <a href="../vista/ver_autogobierno.php"><i class="fas fa-door-open"></i> Resp. Sala Autogobierno</a>
                    <a href="../vista/ver_profesores.php"><i class="fas fa-chalkboard-teacher"></i> Profesor Ed. Física</a>
                </div>
            </div>

            <div class="dropdown">
                <button class="dropbtn">
                    <i class="fas fa-file-pdf"></i> Reportes 
                    <i class="fas fa-caret-down"></i>
                </button>
                <div class="dropdown-content">
                    <a href="../vista/reporte_sesiones.php"><i class="fas fa-history"></i> Reportes de Sesión</a>
                    <a href="../vista/reporte_actividades.php"><i class="fas fa-chart-bar"></i> Reporte de Actividades</a>
                    <div class="dropdown-divider"></div>
                    <a href="../vista/constancia_trabajo.php"><i class="fas fa-file-signature"></i> Constancia de Trabajo</a>
                    <a href="../vista/constancia_participacion.php"><i class="fas fa-certificate"></i> Constancia de Participación</a>
                </div>
            </div>

            <a href="../vista/administrar_usuarios.php" class="nav-link">
                <i class="fas fa-users-cog"></i> Usuarios
            </a>

            <a href="../vista/ayuda.php" class="nav-link">
                <i class="fas fa-question-circle"></i> Ayuda
            </a>
            
            <a href="../controlador/cerrar_sesion.php" class="btn-logout-top">Cerrar Sesión</a>
        </nav>
    </div>
</header>
    <main class="central-container">
        <h1 class="main-title">Panel de Control Deportivo</h1>
    </main>
</body>
</html>