<?php
require_once 'conexion.php';

// 1. Configuración de Paginación
$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$inicio = ($pagina - 1) * $limite;

// 2. Obtener el total de representantes registrados
$sql_total = "SELECT COUNT(*) as total FROM representantes";
$res_total = $conexion->query($sql_total);
$total_registros = $res_total ? $res_total->fetch_assoc()['total'] : 0;
$total_paginas = ceil($total_registros / $limite);

// 3. Consulta paginada con LIMIT y OFFSET
$sql = "SELECT id, cedula, nombre, apellido, telefono, correo, direccion 
        FROM representantes 
        ORDER BY id DESC 
        LIMIT ? OFFSET ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $limite, $inicio);
$stmt->execute();
$resultado = $stmt->get_result();
?>