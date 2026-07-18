<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../controlador/conexion.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID de actividad no especificado.";
    exit();
}

$id_actividad = intval($_GET['id']);

$query = "SELECT a.*, t.nombre_tipo 
          FROM actividades a
          INNER JOIN tipos_actividad t ON a.tipo_id = t.id
          WHERE a.id = ?";
          
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_actividad);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "La actividad solicitada no existe.";
    exit();
}

$act = $resultado->fetch_assoc();
$stmt->close();

$query_responsables = "SELECT e.nombre, e.apellido, e.cargo 
                       FROM empleados e
                       INNER JOIN actividad_responsables ar ON e.id = ar.empleado_id
                       WHERE ar.actividad_id = ?
                       ORDER BY e.nombre ASC";

$stmt_resp = $conexion->prepare($query_responsables);
$stmt_resp->bind_param("i", $id_actividad);
$stmt_resp->execute();
$res_responsables = $stmt_resp->get_result();

$funcionarios_lista = [];
while ($row = $res_responsables->fetch_assoc()) {
    $funcionarios_lista[] = htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) . ' <span class="cargo-subtext">(' . htmlspecialchars($row['cargo']) . ')</span>';
}
$stmt_resp->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Detallado - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style15.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="report-container detail-page">
        <div class="glass-card layout-detail">
            
            <div class="detail-header">
                <img src="../estilo/logo.png" alt="Logo IMDERPE" class="logo-detail">
                <h2>Informe Técnico de Actividad</h2>
                <span class="badge-tipo info-badge"><?php echo htmlspecialchars($act['nombre_tipo']); ?></span>
            </div>

            <div class="detail-grid">
                
                <div class="detail-media">
                    <?php if (!empty($act['foto_actividad']) && file_exists("../fotos_actividades/" . $act['foto_actividad'])): ?>
                        <img src="../fotos_actividades/<?php echo $act['foto_actividad']; ?>" alt="Foto de la actividad" class="img-reporte-grande">
                    <?php else: ?>
                        <div class="no-photo-placeholder">
                            <i class="fas fa-image"></i>
                            <p>Sin fotografía registrada</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="detail-info">
                    <div class="info-block">
                        <label><i class="fas fa-heading"></i> Actividad</label>
                        <p><?php echo htmlspecialchars($act['nombre_actividad']); ?></p>
                    </div>

                    <div class="info-block">
                        <label><i class="fas fa-calendar-day"></i> Fecha de Ejecución</label>
                        <p><?php echo date('d/m/Y', strtotime($act['fecha'])); ?></p>
                    </div>

                    <div class="info-block">
                        <label><i class="fas fa-map-marked-alt"></i> Lugar / Instalación</label>
                        <p><?php echo htmlspecialchars($act['lugar']); ?></p>
                    </div>

                    <div class="info-block">
                        <label><i class="fas fa-user-tie"></i> Funcionarios Responsables</label>
                        <?php if (count($funcionarios_lista) > 0): ?>
                            <?php foreach ($funcionarios_lista as $func): ?>
                                <p style="margin-bottom: 5px;"><?php echo $func; ?></p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Sin responsables asignados</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="detail-full-text">
                    <h3><i class="fas fa-book-open"></i> Reseña Histórica y Balance Técnico</h3>
                    <div class="text-box-resena">
                        <?php if (!empty($act['resena'])): ?>
                            <?php echo nl2br(htmlspecialchars($act['resena'])); ?>
                        <?php else: ?>
                            <p class="no-resena-text">No se adjuntó una reseña histórica al registrar este evento.</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <div class="detail-actions">
                <button onclick="window.close();" class="btn-close-tab">
                    <i class="fas fa-times-circle"></i> Cerrar Pestaña
                </button>
            </div>

        </div>
    </div>
</body>
</html>