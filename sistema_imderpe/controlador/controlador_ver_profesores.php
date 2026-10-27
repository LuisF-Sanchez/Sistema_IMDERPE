<?php
require_once 'conexion.php';

// 1. Configuración de Paginación
$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$inicio = ($pagina - 1) * $limite;

// 2. Obtener el total de profesores registrados
$sql_total = "SELECT COUNT(*) as total FROM profesor_edu";
$res_total = $conexion->query($sql_total);
$total_registros = $res_total ? $res_total->fetch_assoc()['total'] : 0;
$total_paginas = ceil($total_registros / $limite);

// 3. Consulta paginada con LIMIT y OFFSET
$query = "SELECT id, nombre, apellido, cedula, telefono, correo, instituto_educativo 
          FROM profesor_edu 
          ORDER BY nombre ASC 
          LIMIT ? OFFSET ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("ii", $limite, $inicio);
$stmt->execute();
$resultado = $stmt->get_result();
?>