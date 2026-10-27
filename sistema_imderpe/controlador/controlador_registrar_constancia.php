<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}

require_once 'conexion.php';
require_once 'registrar_bitacora.php';

if (file_exists('fpdf/fpdf.php')) {
    require_once 'fpdf/fpdf.php';
} elseif (file_exists('../lib/fpdf/fpdf.php')) {
    require_once '../lib/fpdf/fpdf.php';
} else {
    die("Error: No se encontró la librería FPDF.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_generar'])) {

    $nombre_completo = isset($_POST['nombre_participante']) ? trim($_POST['nombre_participante']) : '';
    $cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');

    if (empty($nombre_completo)) {
        die("Error: No se proporcionó un nombre de participante válido.");
    }

    $nombre_completo = mb_strtoupper($nombre_completo, 'UTF-8');

    $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Administrador');
    $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
    $accion = "Emisión de Constancia";
    $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
    $descripcion = "El {$rol} {$nombre_user} ha generado la constancia de participación para el ciudadano(a) {$nombre_completo}{$cedula_txt}.";

    if (function_exists('registrar_bitacora')) {
        registrar_bitacora($conexion, $accion, $descripcion);
    }

    class PDF_Constancia extends FPDF {
        function Header() {
            $ruta_cintillo = '../estilo/cintillo.jpg';
            if (file_exists($ruta_cintillo)) {
                $this->Image($ruta_cintillo, 0, 0, 210, 25);
            }
            $this->SetY(30);
        }

        function Footer() {
            $this->SetY(-20);
            $this->SetFont('Arial', 'I', 8);
            $this->SetTextColor(128, 128, 128);
            $this->Cell(0, 10, utf8_decode('Documento Oficial - IMDERPE'), 0, 0, 'C');
        }
    }

    $pdf = new PDF_Constancia('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetMargins(20, 30, 20);

    $pdf->Ln(15);
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->SetTextColor(29, 61, 129);
    $pdf->Cell(0, 10, utf8_decode('CONSTANCIA DE PARTICIPACIÓN'), 0, 1, 'C');
    $pdf->Ln(10);

    $meses = [
        '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
        '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
        '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
    ];
    $dia = date('d', strtotime($fecha));
    $num_mes = date('m', strtotime($fecha));
    $anio = date('Y', strtotime($fecha));
    $nombre_mes = isset($meses[$num_mes]) ? $meses[$num_mes] : $num_mes;

    $texto_cedula = !empty($cedula) ? " titular de la Cédula de Identidad V- " . $cedula . "," : "";

    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(40, 40, 40);

    $texto_cuerpo = "El Instituto Autónomo Municipal de Deporte y Recreación (IMDERPE) hace constar que el ciudadano(a):\n\n"
                  . $nombre_completo . $texto_cedula . "\n\n"
                  . "ha participado de manera satisfactoria en las actividades deportivas y recreativas promovidas por esta institución, demostrando compromiso, disciplina y espíritu deportivo.\n\n"
                  . "Constancia que se expide a petición de la parte interesada el día " . $dia . " del mes de " . $nombre_mes . " del año " . $anio . ".";

    $pdf->MultiCell(0, 7, utf8_decode($texto_cuerpo), 0, 'J');

    $pdf->Ln(35);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(85, 5, '________________________', 0, 0, 'C');
    $pdf->Cell(85, 5, '________________________', 0, 1, 'C');
    $pdf->Cell(85, 5, utf8_decode('Dirección de Deportes'), 0, 0, 'C');
    $pdf->Cell(85, 5, utf8_decode('Presidencia IMDERPE'), 0, 1, 'C');

    $pdf->Output('I', 'Constancia_Participacion.pdf');
    exit();
} else {
    header("Location: ../vistas/constancia_participacion.php");
    exit();
}
?>