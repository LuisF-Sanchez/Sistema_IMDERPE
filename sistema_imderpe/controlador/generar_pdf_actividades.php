<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}

require_once 'conexion.php';

if (file_exists('fpdf/fpdf.php')) {
    require_once 'fpdf/fpdf.php';
} elseif (file_exists('../lib/fpdf/fpdf.php')) {
    require_once '../lib/fpdf/fpdf.php';
} else {
    die("Error: No se encontró la librería FPDF. Por favor verifica la ruta de fpdf.php.");
}

$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'mensual';
$fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '';
$fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '';

if (!empty($fecha_desde) && !empty($fecha_hasta)) {
    $filtro_sql = "WHERE a.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta'";
    $titulo_periodo = "PERIODO DEL " . date('d/m/Y', strtotime($fecha_desde)) . " AL " . date('d/m/Y', strtotime($fecha_hasta));
} else {
    switch ($periodo) {
        case 'trimestral':
            $filtro_sql = "WHERE a.fecha >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
            $titulo_periodo = "ÚLTIMOS 3 MESES";
            break;
        case 'anual':
            $filtro_sql = "WHERE YEAR(a.fecha) = YEAR(CURDATE())";
            $titulo_periodo = "AÑO EN CURSO " . date('Y');
            break;
        case 'mensual':
        default:
            $filtro_sql = "WHERE MONTH(a.fecha) = MONTH(CURDATE()) AND YEAR(a.fecha) = YEAR(CURDATE())";
            $meses = [
                1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril", 
                5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto", 
                9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
            ];
            $mes_actual = mb_strtoupper($meses[(int)date('m')]);
            $titulo_periodo = "MES DE " . $mes_actual . " " . date('Y');
            break;
    }
}

$query_tabla = "SELECT a.id, a.nombre_actividad, a.fecha, a.lugar, t.nombre_tipo, 
                       GROUP_CONCAT(CONCAT(e.nombre, ' ', e.apellido) SEPARATOR ', ') AS responsables
                FROM actividades a
                INNER JOIN tipos_actividad t ON a.tipo_id = t.id
                LEFT JOIN actividad_responsables ar ON a.id = ar.actividad_id
                LEFT JOIN empleados e ON ar.empleado_id = e.id
                $filtro_sql 
                GROUP BY a.id, a.nombre_actividad, a.fecha, a.lugar, t.nombre_tipo
                ORDER BY a.fecha DESC";

$res_tabla = $conexion->query($query_tabla);

class PDF extends FPDF {
    
    public $titulo_periodo;

    function Header() {
        $ruta_cintillo = '../estilo/cintillo.jpg'; 
        
        if (file_exists($ruta_cintillo)) {
            $this->Image($ruta_cintillo, 0, 0, 210, 25);
        }

        $this->SetY(28);
 
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(29, 61, 129); 
        $this->Cell(0, 7, utf8_decode('REPORTE AUDITORÍA DE ACTIVIDADES'), 0, 1, 'C');
        
        $this->SetFont('Arial', 'B', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 5, utf8_decode($this->titulo_periodo), 0, 1, 'C');
        
        $this->Ln(5);

        $this->SetFillColor(29, 61, 129);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 9);

        $this->Cell(45, 8, utf8_decode('Actividad'), 1, 0, 'L', true);
        $this->Cell(22, 8, utf8_decode('Fecha'), 1, 0, 'C', true);
        $this->Cell(35, 8, utf8_decode('Lugar'), 1, 0, 'L', true);
        $this->Cell(33, 8, utf8_decode('Tipo'), 1, 0, 'L', true);
        $this->Cell(55, 8, utf8_decode('Responsable(s)'), 1, 1, 'L', true);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    public function GetPageBreakTrigger() {
        return $this->PageBreakTrigger;
    }

    function NbLines($w, $txt) {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n") {
            $nb--;
        }
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') {
                $sep = $i;
            }
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->titulo_periodo = $titulo_periodo;
$pdf->AliasNbPages();
$pdf->SetMargins(10, 55, 10);
$pdf->AddPage();
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(40, 40, 40);

$total_registros = 0;

if ($res_tabla && $res_tabla->num_rows > 0) {
    while ($act = $res_tabla->fetch_assoc()) {
        $total_registros++;
        
        $nombre = utf8_decode($act['nombre_actividad']);
        $fecha = date('d/m/Y', strtotime($act['fecha']));
        $lugar = utf8_decode($act['lugar']);
        $tipo = utf8_decode($act['nombre_tipo']);
        $responsables = !empty($act['responsables']) ? utf8_decode($act['responsables']) : 'Sin asignar';

        $w1 = 45;
        $w2 = 22;
        $w3 = 35;
        $w4 = 33;
        $w5 = 55;

        $nb1 = $pdf->NbLines($w1, $nombre);
        $nb3 = $pdf->NbLines($w3, $lugar);
        $nb4 = $pdf->NbLines($w4, $tipo);
        $nb5 = $pdf->NbLines($w5, $responsables);

        $max_lines = max($nb1, $nb3, $nb4, $nb5);
        $line_height = 5;
        $row_height = $max_lines * $line_height;

        if ($pdf->GetY() + $row_height > $pdf->GetPageBreakTrigger()) {
            $pdf->AddPage();
        }

        $fill = ($total_registros % 2 == 0);
        $pdf->SetFillColor($fill ? 240 : 255, $fill ? 243 : 255, $fill ? 248 : 255);

        $x = $pdf->GetX();
        $y = $pdf->GetY();

        $pdf->Rect($x, $y, $w1, $row_height, $fill ? 'DF' : 'D');
        $pdf->Rect($x + $w1, $y, $w2, $row_height, $fill ? 'DF' : 'D');
        $pdf->Rect($x + $w1 + $w2, $y, $w3, $row_height, $fill ? 'DF' : 'D');
        $pdf->Rect($x + $w1 + $w2 + $w3, $y, $w4, $row_height, $fill ? 'DF' : 'D');
        $pdf->Rect($x + $w1 + $w2 + $w3 + $w4, $y, $w5, $row_height, $fill ? 'DF' : 'D');

        $pdf->MultiCell($w1, $line_height, $nombre, 0, 'L');
        
        $pdf->SetXY($x + $w1, $y);
        $pdf->MultiCell($w2, $line_height, $fecha, 0, 'C');
        
        $pdf->SetXY($x + $w1 + $w2, $y);
        $pdf->MultiCell($w3, $line_height, $lugar, 0, 'L');
        
        $pdf->SetXY($x + $w1 + $w2 + $w3, $y);
        $pdf->MultiCell($w4, $line_height, $tipo, 0, 'L');
        
        $pdf->SetXY($x + $w1 + $w2 + $w3 + $w4, $y);
        $pdf->MultiCell($w5, $line_height, $responsables, 0, 'L');

        $pdf->SetXY($x, $y + $row_height);
    }

    $pdf->Ln(3);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetFillColor(251, 192, 45); 
    $pdf->SetTextColor(29, 61, 129);
    $pdf->Cell(135, 7, utf8_decode('TOTAL DE ACTIVIDADES REGISTRADAS:'), 1, 0, 'R', true);
    $pdf->Cell(55, 7, $total_registros, 1, 1, 'C', true);

} else {
    $pdf->Cell(190, 10, utf8_decode('No se encontraron actividades en el rango seleccionado.'), 1, 1, 'C');
}

$conexion->close();

$pdf->Output('I', 'Reporte_Actividades_' . date('Y-m-d') . '.pdf');
?>