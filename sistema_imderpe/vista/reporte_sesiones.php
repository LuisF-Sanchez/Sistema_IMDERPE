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
$accion_filtro = isset($_GET['accion']) ? trim($_GET['accion']) : '';

$url_params = "";

if (!empty($fecha_desde) && !empty($fecha_hasta)) {
    $filtro_sql = "WHERE DATE(b.fecha_hora) BETWEEN '$fecha_desde' AND '$fecha_hasta'";
    $titulo_grafica = "Periodo del " . date('d/m/Y', strtotime($fecha_desde)) . " al " . date('d/m/Y', strtotime($fecha_hasta));
    $periodo = 'personalizado'; 
    $url_params = "periodo=personalizado&fecha_desde=$fecha_desde&fecha_hasta=$fecha_hasta";
} else {
    switch ($periodo) {
        case 'trimestral':
            $filtro_sql = "WHERE b.fecha_hora >= DATE_SUB(NOW(), INTERVAL 3 MONTH)";
            $titulo_grafica = "Últimos 3 Meses";
            break;
        case 'anual':
            $filtro_sql = "WHERE YEAR(b.fecha_hora) = YEAR(CURDATE())";
            $titulo_grafica = "Año en Curso " . date('Y');
            break;
        case 'mensual':
        default:
            $filtro_sql = "WHERE MONTH(b.fecha_hora) = MONTH(CURDATE()) AND YEAR(b.fecha_hora) = YEAR(CURDATE())";
            
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

if (!empty($accion_filtro)) {
    $url_params .= "&accion=" . urlencode($accion_filtro);
}

$query_grafica = "SELECT b.accion, COUNT(b.id) as total 
                  FROM bitacora_sistema b
                  $filtro_sql 
                  GROUP BY b.accion";
$res_grafica = $conexion->query($query_grafica);

$labels = [];
$valores = [];
$total_general_eventos = 0; 

if ($res_grafica) {
    while ($row = $res_grafica->fetch_assoc()) {
        $labels[] = $row['accion'];
        $valores[] = $row['total'];
        $total_general_eventos += $row['total']; 
    }
}

$filtro_tabla_extra = "";
if (!empty($accion_filtro)) {
    $filtro_tabla_extra = " AND b.accion = '" . $conexion->real_escape_string($accion_filtro) . "'";
}

$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pag']) ? (int)$_GET['pag'] : 1;
if ($pagina_actual < 1) $pagina_actual = 1;

$offset = ($pagina_actual - 1) * $registros_por_pagina;

$sql_total_tabla = "SELECT COUNT(b.id) as total FROM bitacora_sistema b $filtro_sql $filtro_tabla_extra";
$res_total_tabla = $conexion->query($sql_total_tabla);
$total_registros_tabla = $res_total_tabla ? $res_total_tabla->fetch_assoc()['total'] : 0;
$total_paginas = ceil($total_registros_tabla / $registros_por_pagina);

$query_tabla = "SELECT b.id, b.usuario_nombre, b.usuario_rol, b.accion, b.descripcion, b.fecha_hora
                FROM bitacora_sistema b
                $filtro_sql $filtro_tabla_extra
                ORDER BY b.fecha_hora DESC
                LIMIT $registros_por_pagina OFFSET $offset";
$res_tabla = $conexion->query($query_tabla);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Bitácora y Sesiones - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style23.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-scroll-container {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
            padding-bottom: 15px;
            scroll-behavior: smooth;
        }
        .chart-scroll-container::-webkit-scrollbar {
            height: 10px;
        }
        .chart-scroll-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 5px;
        }
        .chart-scroll-container::-webkit-scrollbar-thumb {
            background: rgba(251, 192, 45, 0.7);
            border-radius: 5px;
        }
        .chart-scroll-container::-webkit-scrollbar-thumb:hover {
            background: rgba(251, 192, 45, 1);
        }
        .chart-nav-controls {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 10px;
        }
        .btn-chart-nav {
            background: rgba(251, 192, 45, 0.2);
            border: 1px solid #FBC02D;
            color: #fff;
            padding: 6px 14px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
        }
        .btn-chart-nav:hover {
            background: #FBC02D;
            color: #1D3D81;
        }
    </style>
</head>
<body>
    <div class="report-container">
        
        <div class="glass-card tools-section">
            <div class="header-filters-layout">
                <div class="filter-buttons">
                    <a href="?periodo=mensual" class="btn-filter <?php echo ($periodo == 'mensual' && empty($accion_filtro)) ? 'active' : ''; ?>">Mensual</a>
                    <a href="?periodo=trimestral" class="btn-filter <?php echo ($periodo == 'trimestral' && empty($accion_filtro)) ? 'active' : ''; ?>">Trimestral</a>
                    <a href="?periodo=anual" class="btn-filter <?php echo ($periodo == 'anual' && empty($accion_filtro)) ? 'active' : ''; ?>">Anual</a>
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
                <h2><i class="fas fa-chart-pie"></i> Movimientos en Bitácora: <?php echo $titulo_grafica; ?></h2>
            </div>
            
            <div id="statusFiltro" style="text-align: center; color: #FBC02D; font-weight: bold; font-size: 0.95rem; min-height: 20px; margin-bottom: 5px;">
                <?php if (!empty($accion_filtro)): ?>
                    <i class="fas fa-filter"></i> Enfocado en: <?php echo htmlspecialchars($accion_filtro); ?> &nbsp;|&nbsp; 
                    <a href="?<?php echo $periodo == 'personalizado' ? "periodo=personalizado&fecha_desde=$fecha_desde&fecha_hasta=$fecha_hasta" : "periodo=$periodo"; ?>" style="color: #FBC02D; text-decoration: underline;">Mostrar todos</a>
                <?php endif; ?>
            </div>

            <div class="chart-nav-controls">
                <button type="button" class="btn-chart-nav" onclick="resetChartScroll()">
                    <i class="fas fa-home"></i> Volver al inicio
                </button>
                <button type="button" class="btn-chart-nav" onclick="scrollChartLeft()">
                    <i class="fas fa-arrow-left"></i> Anterior
                </button>
                <button type="button" class="btn-chart-nav" onclick="scrollChartRight()">
                    Siguiente sección <i class="fas fa-arrow-right"></i>
                </button>
            </div>

            <div class="chart-scroll-container" id="chartScrollContainer">
                <div class="chart-wrapper" id="chartWrapper" style="position: relative; margin-bottom: 10px; height: 380px;">
                    <canvas id="chartBitacora"></canvas>
                </div>
            </div>
            
            <div class="total-unificado-container" style="display: flex; justify-content: center; align-items: center; margin-top: 15px; margin-bottom: 5px;">
                <div style="background: rgba(29, 61, 129, 0.85); border: 1px solid rgba(255, 255, 255, 0.2); padding: 8px 25px; border-radius: 30px; color: white; font-weight: bold; font-size: 1.05rem; box-shadow: 0 4px 15px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-shield-alt" style="color: #FBC02D;"></i>
                    <span id="texto-total-dinamico">
                        <?php echo !empty($accion_filtro) ? "Total de " . htmlspecialchars($accion_filtro) . ":" : "Total Eventos Registrados:"; ?>
                    </span>
                    <span id="valor-total-dinamico" style="background: #FBC02D; color: #1D3D81; padding: 2px 12px; border-radius: 15px; font-size: 1.1rem; font-weight: 800; min-width: 30px; text-align: center;">
                        <?php echo !empty($accion_filtro) ? $total_registros_tabla : $total_general_eventos; ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="glass-card table-section">
            <div class="table-header-actions">
                <h3><i class="fas fa-history"></i> Historial de Movimientos</h3>
            </div>

            <div class="table-responsive">
                <table id="tablaBitacora">
                    <thead>
                        <tr>
                            <th>Fecha y Hora</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Acción</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res_tabla && $res_tabla->num_rows > 0): ?>
                            <?php while ($bit = $res_tabla->fetch_assoc()): ?>
                                <tr>
                                    <td><i class="far fa-clock" style="color: #FBC02D; font-size: 0.85rem;"></i> <?php echo date('d/m/Y h:i A', strtotime($bit['fecha_hora'])); ?></td>
                                    <td><strong><?php echo htmlspecialchars($bit['usuario_nombre']); ?></strong></td>
                                    <td><span class="badge-rol"><?php echo htmlspecialchars(ucfirst($bit['usuario_rol'])); ?></span></td>
                                    <td><span class="badge-accion"><?php echo htmlspecialchars($bit['accion']); ?></span></td>
                                    <td class="text-descripcion"><?php echo htmlspecialchars($bit['descripcion']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="no-data">No se registraron movimientos en la bitácora dentro del periodo seleccionado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_paginas > 1): ?>
                <div class="paginacion-container">
                    <?php if ($pagina_actual > 1): ?>
                        <a href="?<?php echo $url_params; ?>&pag=<?php echo $pagina_actual - 1; ?>" class="btn-pag">&laquo; Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <a href="?<?php echo $url_params; ?>&pag=<?php echo $i; ?>" class="btn-pag <?php echo ($i == $pagina_actual) ? 'activa' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($pagina_actual < $total_paginas): ?>
                        <a href="?<?php echo $url_params; ?>&pag=<?php echo $pagina_actual + 1; ?>" class="btn-pag">Siguiente &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="center-back">
                <a href="inicio.php" class="btn-back"><i class="fas fa-arrow-left"></i> Volver al Inicio</a>
            </div>
        </div>

    </div>

    <script>
        const labelsAcciones = <?php echo json_encode($labels); ?>;
        const datosTotales = <?php echo json_encode($valores); ?>;
        const accionActual = "<?php echo $accion_filtro; ?>";

        const finalLabels = labelsAcciones.length > 0 ? labelsAcciones : ['Sin registros'];
        const finalData = datosTotales.length > 0 ? datosTotales : [0];

        const anchoMinimoPorBarra = 90;
        const anchoCalculado = Math.max(700, finalLabels.length * anchoMinimoPorBarra);
        
        const wrapper = document.getElementById('chartWrapper');
        const canvas = document.getElementById('chartBitacora');
        wrapper.style.width = anchoCalculado + 'px';
        canvas.style.width = anchoCalculado + 'px';
        canvas.style.height = '360px';

        const ctx = canvas.getContext('2d');

        const listaColores = [
            'rgba(251, 192, 45, 0.7)',
            'rgba(40, 167, 69, 0.7)',
            'rgba(23, 162, 184, 0.7)',
            'rgba(0, 123, 255, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(220, 53, 69, 0.7)',
            'rgba(255, 193, 7, 0.7)'
        ];
        const listaBordes = ['#FBC02D', '#28a745', '#17a2b8', '#007bff', '#9966ff', '#dc3545', '#ffc107'];

        let bgColores = finalData.map((_, i) => {
            if (accionActual && finalLabels[i] === accionActual) {
                return listaColores[i % listaColores.length].replace('0.7', '0.95');
            }
            return accionActual ? listaColores[i % listaColores.length].replace('0.7', '0.15') : listaColores[i % listaColores.length];
        });

        let bdColores = finalData.map((_, i) => {
            if (accionActual && finalLabels[i] === accionActual) {
                return listaBordes[i % listaBordes.length];
            }
            return accionActual ? 'rgba(255, 255, 255, 0.05)' : listaBordes[i % listaBordes.length];
        });

        const miGrafica = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: finalLabels,
                datasets: [{
                    label: 'Registros de Bitácora',
                    data: finalData,
                    backgroundColor: bgColores,
                    borderColor: bdColores,
                    borderWidth: 1.5,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        bottom: 25 // Margen inferior para que las etiquetas no se corten
                    }
                },
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
                        const clickedAccion = finalLabels[index];
                        
                        let baseUrl = window.location.pathname + "?<?php echo $periodo == 'personalizado' ? "periodo=personalizado&fecha_desde=$fecha_desde&fecha_hasta=$fecha_hasta" : "periodo=$periodo"; ?>";

                        if (accionActual === clickedAccion) {
                            window.location.href = baseUrl;
                        } else {
                            window.location.href = baseUrl + "&accion=" + encodeURIComponent(clickedAccion);
                        }
                    }
                }
            }
        });

        function scrollChartRight() {
            const container = document.getElementById('chartScrollContainer');
            container.scrollBy({ left: container.clientWidth, behavior: 'smooth' });
        }

        function scrollChartLeft() {
            const container = document.getElementById('chartScrollContainer');
            container.scrollBy({ left: -container.clientWidth, behavior: 'smooth' });
        }

        function resetChartScroll() {
            const container = document.getElementById('chartScrollContainer');
            container.scrollTo({ left: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>