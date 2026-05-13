<?php
require_once 'conexion.php';

$sql = "SELECT 
            a.id, 
            a.cedula, 
            a.nombre, 
            a.apellido, 
            a.fecha_nacimiento,
            a.estado,
            r.nombre AS nombre_rep, 
            r.apellido AS apellido_rep,
            e.nombre AS nombre_ent, 
            e.apellido AS apellido_ent,
            d.nombre_disciplina AS disciplina
        FROM atletas a
        INNER JOIN representantes r ON a.representante_id = r.id
        INNER JOIN entrenadores e ON a.entrenador_id = e.id
        INNER JOIN disciplinas d ON a.disciplina_id = d.id";

$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en la consulta: " . $conexion->error);
}
?>