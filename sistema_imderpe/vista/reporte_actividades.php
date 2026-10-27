<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}
require_once '../controlador/conexion.php';

$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'mensual';
$fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '';
$fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '';
$tipo_filtro = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';

$url_params = "";

if (!empty($fecha_desde) && !empty($fecha_hasta)) {
    $filtro_sql = "WHERE a.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta'";
    $titulo_grafica = "Periodo del " . date('d/m/Y', strtotime($fecha_desde)) . " al " . date('d/m/Y', strtotime($fecha_hasta));
    $periodo = 'personalizado'; 
    $url_params = "periodo=personalizado&fecha_desde=$fecha_desde&fecha_hasta=$fecha_hasta";
} else {
    switch ($periodo) {
        case 'trimestral':
            $filtro_sql = "WHERE a.fecha >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
            $titulo_grafica = "Últimos 3 Meses";
            break;
        case 'anual':
            $filtro_sql = "WHERE YEAR(a.fecha) = YEAR(CURDATE())";
            $titulo_grafica = "Año en Curso " . date('Y');
            break;
        case 'mensual':
        default:
            $filtro_sql = "WHERE MONTH(a.fecha) = MONTH(CURDATE()) AND YEAR(a.fecha) = YEAR(CURDATE())";
            
            $meses = [
                1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 
                5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 
                9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
            ];
            $mes_actual = $meses[(int)date('m')];
            $titulo_grafica = "Mes de " . $mes_actual . " " . date('Y');
            
            $periodo = 'mensual';
            break;
    }
    $url_params = "periodo=$periodo";
}

// Mantener el parámetro de tipo en la URL si está seleccionado
if (!empty($tipo_filtro)) {
    $url_params .= "&tipo=" . urlencode($tipo_filtro);
}

// 1. Obtener datos para la gráfica general
$query_grafica = "SELECT t.nombre_tipo, COUNT(a.id) as total 
                  FROM actividades a
                  INNER JOIN tipos_actividad t ON a.tipo_id = t.id 
                  $filtro_sql 
                  GROUP BY t.id";
$res_grafica = $conexion->query($query_grafica);

$labels = [];
$valores = [];
$total_general_actividades = 0; 

while ($row = $res_grafica->fetch_assoc()) {
    $labels[] = $row['nombre_tipo'];
    $valores[] = $row['total'];
    $total_general_actividades += $row['total']; 
}

// 2. Filtro extra para la tabla si se seleccionó una barra específica
$filtro_tipo_sql = "";
if (!empty($tipo_filtro)) {
    $filtro_tipo_sql = " AND t.nombre_tipo = '" . $conexion->real_escape_string($tipo_filtro) . "'";
}

// 3. Configuración de Paginación (10 registros por página)
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pag']) ? (int)$_GET['pag'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// Total de registros para la tabla según el filtro
$sql_total_tabla = "SELECT COUNT(a.id) as total 
                    FROM actividades a
                    INNER JOIN tipos_actividad t ON a.tipo_id = t.id
                    $filtro_sql $filtro_tipo_sql";
$res_total_tabla = $conexion->query($sql_total_tabla);
$total_registros_tabla = $res_total_tabla ? $res_total_tabla->fetch_assoc()['total'] : 0;
$total_paginas = ceil($total_registros_tabla / $registros_por_pagina);

// Consulta paginada para la tabla
$query_tabla = "SELECT a.id, a.nombre_actividad, a.fecha, a.lugar, t.nombre_tipo, 
                       GROUP_CONCAT(CONCAT(e.nombre, ' ', e.apellido) SEPARATOR ', ') AS responsables
                FROM actividades a
                INNER JOIN tipos_actividad t ON a.tipo_id = t.id
                LEFT JOIN actividad_responsables ar ON a.id = ar.actividad_id
                LEFT JOIN empleados e ON ar.empleado_id = e.id
                $filtro_sql $filtro_tipo_sql
                GROUP BY a.id, t.nombre_tipo
                ORDER BY a.fecha DESC
                LIMIT $registros_por_pagina OFFSET $offset";
$res_tabla = $conexion->query($query_tabla);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Actividades - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style15.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="report-container">
        
        <div class="glass-card tools-section">
            <div class="header-filters-layout">
                <div class="filter-buttons">
                    <a href="?periodo=mensual" class="btn-filter <?php echo ($periodo == 'mensual' && empty($tipo_filtro)) ? 'active' : ''; ?>">Mensual</a>
                    <a href="?periodo=trimestral" class="btn-filter <?php echo ($periodo == 'trimestral' && empty($tipo_filtro)) ? 'active' : ''; ?>">Trimestral</a>
                    <a href="?periodo=anual" class="btn-filter <?php echo ($periodo == 'anual' && empty($tipo_filtro)) ? 'active' : ''; ?>">Anual</a>
                </div>

                <form action="" method="GET" class="date-range-form">
                    <div class="range-inputs">
                        <div class="inline-input">
                            <label>Desde:</label>
                            <input type="date" name="fecha_desde" value="<?php echo $fecha_desde; ?>" required>
                        </div>
                        <div class="inline-input">
                            <label>Hasta:</label>
                            <input type="date" name="fecha_hasta" value="<?php echo $fecha_hasta; ?>" required>
                        </div>
                        <button type="submit" class="btn-search-range">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="glass-card chart-section">
            <div class="header-report">
                <h2><i class="fas fa-chart-bar"></i> Estadísticas: <?php echo $titulo_grafica; ?></h2>
            </div>
            
            <div id="statusFiltro" style="text-align: center; color: #FBC02D; font-weight: bold; font-size: 0.95rem; min-height: 20px; margin-bottom: 5px;">
                <?php if (!empty($tipo_filtro)): ?>
                    <i class="fas fa-filter"></i> Enfocado en: <?php echo htmlspecialchars($tipo_filtro); ?> &nbsp;|&nbsp; 
                    <a href="?<?php echo $periodo == 'personalizado' ? "periodo=personalizado&fecha_desde=$fecha_desde&fecha_hasta=$fecha_hasta" : "periodo=$periodo"; ?>" style="color: #FBC02D; text-decoration: underline;">Mostrar todos</a>
                <?php endif; ?>
            </div>

            <div class="chart-wrapper" style="position: relative; margin-bottom: 10px;">
                <canvas id="chartActividades"></canvas>
            </div>
            
            <div class="total-unificado-container" style="display: flex; justify-content: center; align-items: center; margin-top: 15px; margin-bottom: 5px;">
                <div style="background: rgba(29, 61, 129, 0.85); border: 1px solid rgba(255, 255, 255, 0.2); padding: 8px 25px; border-radius: 30px; color: white; font-weight: bold; font-size: 1.05rem; box-shadow: 0 4px 15px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-calculator" style="color: #FBC02D;"></i>
                    <span id="texto-total-dinamico">
                        <?php echo !empty($tipo_filtro) ? "Total de " . htmlspecialchars($tipo_filtro) . ":" : "Total General Unificado:"; ?>
                    </span>
                    <span id="valor-total-dinamico" style="background: #FBC02D; color: #1D3D81; padding: 2px 12px; border-radius: 15px; font-size: 1.1rem; font-weight: 800; min-width: 30px; text-align: center;">
                        <?php echo !empty($tipo_filtro) ? $total_registros_tabla : $total_general_actividades; ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="glass-card table-section">
            <div class="table-header-actions">
                <h3><i class="fas fa-list-alt"></i> Detalles de Auditoría</h3>
                <a href="../controlador/generar_pdf_actividades.php?<?php echo $url_params; ?>" target="_blank" class="btn-pdf">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </a>
            </div>

            <div class="table-responsive">
                <table id="tablaAuditoria">
                    <thead>
                        <tr>
                            <th>Actividad</th>
                            <th>Fecha</th>
                            <th>Lugar</th>
                            <th>Tipo</th>
                            <th>Empleado(s) Responsable(s)</th>
                            <th>Informe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res_tabla && $res_tabla->num_rows > 0): ?>
                            <?php while ($act = $res_tabla->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($act['nombre_actividad']); ?></strong></td>
                                    <td><?php echo date('d/m/Y', strtotime($act['fecha'])); ?></td>
                                    <td><?php echo htmlspecialchars($act['lugar']); ?></td>
                                    <td><span class="badge-tipo"><?php echo htmlspecialchars($act['nombre_tipo']); ?></span></td>
                                    <td><?php echo !empty($act['responsables']) ? htmlspecialchars($act['responsables']) : 'Sin responsables asignados'; ?></td>
                                    <td>
                                        <a href="detalle_actividad.php?id=<?php echo $act['id']; ?>" target="_blank" class="link-ver-mas">
                                            <i class="fas fa-eye"></i> Ver más
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="no-data">No se encontraron actividades en el rango seleccionado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Barra de Paginación -->
            <?php if ($total_paginas > 1): ?>
                <div class="paginacion-container" style="display: flex; justify-content: center; gap: 8px; margin-top: 20px;">
                    <?php if ($pagina_actual > 1): ?>
                        <a href="?<?php echo $url_params; ?>&pag=<?php echo $pagina_actual - 1; ?>" class="btn-pag" style="padding: 6px 12px; background: rgba(255,255,255,0.1); color:white; border-radius:6px; text-decoration:none;">&laquo; Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <a href="?<?php echo $url_params; ?>&pag=<?php echo $i; ?>" class="btn-pag <?php echo ($i == $pagina_actual) ? 'activa' : ''; ?>" style="padding: 6px 12px; background: <?php echo ($i == $pagina_actual) ? '#FBC02D' : 'rgba(255,255,255,0.1)'; ?>; color: <?php echo ($i == $pagina_actual) ? '#1D3D81' : 'white'; ?>; border-radius:6px; text-decoration:none; font-weight:bold;">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($pagina_actual < $total_paginas): ?>
                        <a href="?<?php echo $url_params; ?>&pag=<?php echo $pagina_actual + 1; ?>" class="btn-pag" style="padding: 6px 12px; background: rgba(255,255,255,0.1); color:white; border-radius:6px; text-decoration:none;">Siguiente &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="center-back">
                <a href="inicio.php" class="btn-back"><i class="fas fa-arrow-left"></i> Volver al Inicio</a>
            </div>
        </div>

    </div>

    <script>
        const ctx = document.getElementById('chartActividades').getContext('2d');
        const labelsTipos = <?php echo json_encode($labels); ?>;
        const datosTotales = <?php echo json_encode($valores); ?>;
        const tipoActual = "<?php echo $tipo_filtro; ?>";

        const finalLabels = labelsTipos.length > 0 ? labelsTipos : ['Sin datos en el rango'];
        const finalData = datosTotales.length > 0 ? datosTotales : [0];

        const listaColores = [
            'rgba(251, 192, 45, 0.7)',
            'rgba(40, 167, 69, 0.7)',
            'rgba(23, 162, 184, 0.7)',
            'rgba(0, 123, 255, 0.7)',
            'rgba(153, 102, 255, 0.7)'
        ];
        const listaBordes = ['#FBC02D', '#28a745', '#17a2b8', '#007bff', '#9966ff'];

        let bgColores = finalData.map((_, i) => {
            if (tipoActual && finalLabels[i] === tipoActual) {
                return listaColores[i % listaColores.length].replace('0.7', '0.95');
            }
            return tipoActual ? listaColores[i % listaColores.length].replace('0.7', '0.15') : listaColores[i % listaColores.length];
        });

        let bdColores = finalData.map((_, i) => {
            if (tipoActual && finalLabels[i] === tipoActual) {
                return listaBordes[i % listaBordes.length];
            }
            return tipoActual ? 'rgba(255, 255, 255, 0.05)' : listaBordes[i % listaBordes.length];
        });

        const miGrafica = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: finalLabels,
                datasets: [{
                    label: 'Suma de Actividades',
                    data: finalData,
                    backgroundColor: bgColores,
                    borderColor: bdColores,
                    borderWidth: 1.5,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#ffffff', stepSize: 1 }, 
                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                    },
                    x: {
                        ticks: { color: '#ffffff' },
                        grid: { display: false }
                    }
                },
                onClick: (event, elements) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const clickedTipo = finalLabels[index];
                        
                        let baseUrl = window.location.pathname + "?<?php echo $periodo == 'personalizado' ? "periodo=personalizado&fecha_desde=$fecha_desde&fecha_hasta=$fecha_hasta" : "periodo=$periodo"; ?>";

                        // Si hace clic de nuevo en la misma barra activa, limpia el filtro (vuelve al estado normal)
                        if (tipoActual === clickedTipo) {
                            window.location.href = baseUrl;
                        } else {
                            // Si hace clic en otra barra, aplica el filtro nuevo
                            window.location.href = baseUrl + "&tipo=" + encodeURIComponent(clickedTipo);
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>