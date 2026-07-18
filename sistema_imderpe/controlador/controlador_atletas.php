<?php
require_once 'conexion.php';

$disciplina_filtro = isset($_GET['disciplina']) ? $_GET['disciplina'] : '';
$genero_filtro = isset($_GET['genero']) ? $_GET['genero'] : '';
$categoria_filtro = isset($_GET['categoria']) ? $_GET['categoria'] : '';

$sql = "SELECT 
            a.id, 
            a.cedula, 
            a.nombre, 
            a.apellido, 
            a.genero,
            a.fecha_nacimiento,
            a.comuna,
            a.categoria,
            a.estado,
            r.nombre AS nombre_rep, 
            r.apellido AS apellido_rep,
            e.nombre AS nombre_ent, 
            e.apellido AS apellido_ent,
            d.nombre_disciplina AS disciplina
        FROM atletas a
        INNER JOIN representantes r ON a.representante_id = r.id
        INNER JOIN entrenadores e ON a.entrenador_id = e.id
        INNER JOIN disciplinas d ON a.disciplina_id = d.id WHERE 1=1";

$params = [];
$types = "";

if (!empty($disciplina_filtro)) {
    $sql .= " AND a.disciplina_id = ?";
    $params[] = $disciplina_filtro;
    $types .= "i";
}

if (!empty($genero_filtro)) {
    $sql .= " AND a.genero = ?";
    $params[] = $genero_filtro;
    $types .= "s";
}

if (!empty($categoria_filtro)) {
    $sql .= " AND a.categoria = ?";
    $params[] = $categoria_filtro;
    $types .= "s";
}

$sql .= " ORDER BY a.id DESC";

$stmt = $conexion->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$resultado = $stmt->get_result();

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}

$sql_disc = "SELECT id, nombre_disciplina FROM disciplinas";
$resultado_disciplinas = $conexion->query($sql_disc);
?>