<?php
require_once '../controlador/conexion.php';

// Filtros GET
$disciplina_filtro = isset($_GET['disciplina']) ? trim($_GET['disciplina']) : '';
$genero_filtro = isset($_GET['genero']) ? trim($_GET['genero']) : '';
$categoria_filtro = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';

// 1. Paginación
$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$inicio = ($pagina - 1) * $limite;

// Construir cláusula WHERE dinámica
$condiciones = [];
$params = [];
$tipos = "";

if (!empty($disciplina_filtro)) {
    $condiciones[] = "a.disciplina_id = ?";
    $params[] = $disciplina_filtro;
    $tipos .= "i";
}
if (!empty($genero_filtro)) {
    $condiciones[] = "a.genero = ?";
    $params[] = $genero_filtro;
    $tipos .= "s";
}
if (!empty($categoria_filtro)) {
    $condiciones[] = "a.categoria = ?";
    $params[] = $categoria_filtro;
    $tipos .= "s";
}

$where_sql = count($condiciones) > 0 ? "WHERE " . implode(" AND ", $condiciones) : "";

// 2. Contar total de registros filtrados
$sql_total = "SELECT COUNT(*) as total FROM atletas a $where_sql";
$stmt_total = $conexion->prepare($sql_total);
if (!empty($tipos)) {
    $stmt_total->bind_param($tipos, ...$params);
}
$stmt_total->execute();
$total_registros = $stmt_total->get_result()->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $limite);
$stmt_total->close();

// 3. Consulta paginada de Atletas
$sql_atletas = "SELECT a.id, a.cedula, a.nombre, a.apellido, a.genero, a.fecha_nacimiento, a.comuna, a.categoria, a.estado,
                       r.nombre AS nombre_rep, r.apellido AS apellido_rep,
                       e.nombre AS nombre_ent, e.apellido AS apellido_ent,
                       d.nombre_disciplina AS disciplina
                FROM atletas a
                LEFT JOIN representantes r ON a.representante_id = r.id
                LEFT JOIN entrenadores e ON a.entrenador_id = e.id
                LEFT JOIN disciplinas d ON a.disciplina_id = d.id
                $where_sql
                ORDER BY a.id DESC
                LIMIT ? OFFSET ?";

$params_atletas = $params;
$params_atletas[] = $limite;
$params_atletas[] = $inicio;
$tipos_atletas = $tipos . "ii";

$stmt_atletas = $conexion->prepare($sql_atletas);
$stmt_atletas->bind_param($tipos_atletas, ...$params_atletas);
$stmt_atletas->execute();
$resultado = $stmt_atletas->get_result();

// Cargar disciplinas para el desplegable de filtros
$resultado_disciplinas = $conexion->query("SELECT id, nombre_disciplina FROM disciplinas ORDER BY nombre_disciplina ASC");
?>