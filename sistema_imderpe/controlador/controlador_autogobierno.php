<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'conexion.php';

// 1. Configuración de Paginación
$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$inicio = ($pagina - 1) * $limite;

// 2. Obtener el total de registros para la paginación
$sql_total = "SELECT COUNT(*) as total FROM autogobierno";
$res_total = $conexion->query($sql_total);
$total_registros = $res_total ? $res_total->fetch_assoc()['total'] : 0;
$total_paginas = ceil($total_registros / $limite);

// 3. Consulta paginada con LIMIT y OFFSET
$query = "SELECT id, nombre, apellido, cedula, telefono, correo, direccion, comuna 
          FROM autogobierno 
          ORDER BY nombre ASC, apellido ASC
          LIMIT ? OFFSET ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("ii", $limite, $inicio);
$stmt->execute();
$resultado = $stmt->get_result();
?>