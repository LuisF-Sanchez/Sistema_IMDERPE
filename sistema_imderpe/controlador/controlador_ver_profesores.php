<?php
require_once 'conexion.php';

$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1)$pagina = 1;
$inicio = ($pagina - 1) *$limite;

$sql_total = "SELECT COUNT(*) as total FROM profesor_edu";
$res_total =$conexion->query($sql_total);$total_registros = $res_total ? $res_total->fetch_assoc()['total'] : 0;
$total_paginas = ceil($total_registros / $limite);

$query = "SELECT id, nombre, apellido, cedula, telefono, correo, instituto_educativo 
          FROM profesor_edu 
          ORDER BY nombre ASC 
          LIMIT ? OFFSET ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("ii", $limite, $inicio);$stmt->execute();
$resultado =$stmt->get_result();
?>