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

$query_tabla = "SELECT a.nombre_actividad, a.fecha, a.lugar, t.nombre_tipo, e.nombre, e.apellido 
                FROM actividades a
                INNER JOIN tipos_actividad t ON a.tipo_id = t.id
                INNER JOIN empleados e ON a.empleado_id = e.id
                $filtro_sql 
                ORDER BY a.fecha DESC";
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
                    <a href="?periodo=mensual" class="btn-filter <?php echo $periodo == 'mensual' ? 'active' : ''; ?>">Mensual</a>
                    <a href="?periodo=trimestral" class="btn-filter <?php echo $periodo == 'trimestral' ? 'active' : ''; ?>">Trimestral</a>
                    <a href="?periodo=anual" class="btn-filter <?php echo $periodo == 'anual' ? 'active' : ''; ?>">Anual</a>
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
            
            <div id="statusFiltro" style="text-align: center; color: #FBC02D; font-weight: bold; font-size: 0.95rem; min-height: 20px; margin-bottom: 5px;"></div>

            <div class="chart-wrapper" style="position: relative; margin-bottom: 10px;">
                <canvas id="chartActividades"></canvas>
            </div>
            
            <div class="total-unificado-container" style="display: flex; justify-content: center; align-items: center; margin-top: 15px; margin-bottom: 5px;">
                <div style="background: rgba(29, 61, 129, 0.85); border: 1px solid rgba(255, 255, 255, 0.2); padding: 8px 25px; border-radius: 30px; color: white; font-weight: bold; font-size: 1.05rem; box-shadow: 0 4px 15px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-calculator" style="color: #FBC02D;"></i>
                    <span>Total General Unificado:</span>
                    <span style="background: #FBC02D; color: #1D3D81; padding: 2px 12px; border-radius: 15px; font-size: 1.1rem; font-weight: 800; min-width: 30px; text-align: center;">
                        <?php echo $total_general_actividades; ?>
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
                            <th>Empleado Responsable</th>
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
                                    <td><?php echo htmlspecialchars($act['nombre'] . ' ' . $act['apellido']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="no-data">No se encontraron actividades en el rango seleccionado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="center-back">
                <a href="inicio.php" class="btn-back"><i class="fas fa-arrow-left"></i> Volver al Inicio</a>
            </div>
        </div>

    </div>

    <script>
        const ctx = document.getElementById('chartActividades').getContext('2d');
        const labelsTipos = <?php echo json_encode($labels); ?>;
        const datosTotales = <?php echo json_encode($valores); ?>;

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
        let indiceFiltrado = null;

        const miGrafica = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: finalLabels,
                datasets: [{
                    label: 'Suma de Actividades',
                    data: finalData,
                    backgroundColor: [...listaColores],
                    borderColor: [...listaBordes],
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
                onHover: (event, elements) => {
                    if (indiceFiltrado !== null) return;

                    if (elements.length > 0) {
                        const idx = elements[0].index;
                        const nuevosColores = finalData.map((_, i) => 
                            i === idx ? listaColores[i].replace('0.7', '0.95') : listaColores[i].replace('0.7', '0.15')
                        );
                        const nuevosBordes = finalData.map((_, i) => 
                            i === idx ? listaBordes[i] : 'rgba(255, 255, 255, 0.05)'
                        );
                        
                        miGrafica.data.datasets[0].backgroundColor = nuevosColores;
                        miGrafica.data.datasets[0].borderColor = nuevosBordes;
                    } else {
                        miGrafica.data.datasets[0].backgroundColor = [...listaColores];
                        miGrafica.data.datasets[0].borderColor = [...listaBordes];
                    }
                    miGrafica.update('none');
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

                        if (indiceFiltrado === index) {
                            revertirFiltroExterno();
                        } else {
                            const tipoSeleccionado = finalLabels[index];
                            miGrafica.data.labels = [tipoSeleccionado];
                            miGrafica.data.datasets[0].data = [finalData[index]];
                            miGrafica.data.datasets[0].backgroundColor = [listaColores[index]];
                            miGrafica.data.datasets[0].borderColor = [listaBordes[index]];
                            indiceFiltrado = index;
                            
                            document.getElementById('statusFiltro').innerHTML = 
                                `<i class="fas fa-filter"></i> Enfocado en: ${tipoSeleccionado} &nbsp;|&nbsp; <span style="cursor:pointer; text-decoration: underline;" onclick="revertirFiltroExterno()">Mostrar todos</span>`;
                            
                            filtrarTablaHTML(tipoSeleccionado);
                            miGrafica.update();
                        }
                    }
                }
            }
        });

        function filtrarTablaHTML(tipo) {
            const filas = document.querySelectorAll('#tablaAuditoria tbody tr');
            filas.forEach(fila => {
                const badge = fila.querySelector('.badge-tipo');
                if (badge) {
                    if (badge.textContent.trim() === tipo) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                }
            });
        }

        function revertirFiltroExterno() {
            miGrafica.data.labels = finalLabels;
            miGrafica.data.datasets[0].data = finalData;
            miGrafica.data.datasets[0].backgroundColor = [...listaColores];
            miGrafica.data.datasets[0].borderColor = [...listaBordes];
            indiceFiltrado = null;
            document.getElementById('statusFiltro').innerText = "";
            
            const filas = document.querySelectorAll('#tablaAuditoria tbody tr');
            filas.forEach(fila => fila.style.display = '');
            
            miGrafica.update();
        }
    </script>
</body>
</html>