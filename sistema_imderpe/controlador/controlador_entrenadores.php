<?php
require_once 'conexion.php';

$sql = "SELECT 
            e.id, 
            e.cedula, 
            e.nombre, 
            e.apellido, 
            e.telefono, 
            e.correo, 
            e.estado, 
            d.nombre_disciplina 
        FROM entrenadores e
        LEFT JOIN disciplinas d ON e.disciplina_id = d.id
        ORDER BY e.id DESC";

$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}
?>