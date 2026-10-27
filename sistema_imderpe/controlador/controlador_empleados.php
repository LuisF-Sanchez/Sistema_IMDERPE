<?php
require_once '../controlador/conexion.php';

// 1. Configurar límite por página
$limite = 10; 
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$inicio = ($pagina - 1) * $limite;

// 2. Obtener total de registros para calcular número de páginas
$sql_total = "SELECT COUNT(*) as total FROM empleados";
$res_total = $conexion->query($sql_total);
$total_registros = $res_total->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $limite);

// 3. Consulta paginada con LIMIT y OFFSET
$sql = "SELECT id, cedula, nombre, apellido, cargo, telefono, correo, estado, foto, fecha_ingreso 
        FROM empleados 
        ORDER BY id DESC 
        LIMIT $limite OFFSET $inicio";

$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}
?>