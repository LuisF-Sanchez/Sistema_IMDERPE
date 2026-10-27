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

// 2. Obtener el total de entrenadores registrados
$sql_total = "SELECT COUNT(*) as total FROM entrenadores";
$res_total = $conexion->query($sql_total);
$total_registros = $res_total ? $res_total->fetch_assoc()['total'] : 0;
$total_paginas = ceil($total_registros / $limite);

// 3. Consulta paginada uniendo la disciplina
$query = "SELECT e.id, e.cedula, e.nombre, e.apellido, e.telefono, e.correo, e.estado, d.nombre_disciplina 
          FROM entrenadores e
          LEFT JOIN disciplinas d ON e.disciplina_id = d.id
          ORDER BY e.id DESC
          LIMIT ? OFFSET ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("ii", $limite, $inicio);
$stmt->execute();
$resultado = $stmt->get_result();
?>