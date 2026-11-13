<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'conexion.php';

// 1. Configuración de Paginación
$limite = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;

// 2. Obtener el total de registros y calcular el total de páginas
$sql_total = "SELECT COUNT(*) as total FROM usuarios";
$res_total = $conexion->query($sql_total);
$total_registros = $res_total ? $res_total->fetch_assoc()['total'] : 0;
$total_paginas = ceil($total_registros / $limite);

// 3. CORRECCIÓN INTELIGENTE: Si la página solicitada es mayor al total de páginas existentes (ej. eliminaste el último de la sección 2)
if ($total_paginas > 0 && $pagina > $total_paginas) {
    $pagina = $total_paginas; // Redirigir a la última página válida
    header("Location: ../vista/administrar_usuarios.php?pagina={$pagina}&eliminar_exito=ok");
    exit();
}

$inicio = ($pagina - 1) * $limite;

// 4. Consulta paginada con LIMIT y OFFSET
$query = "SELECT id, nombre, cedula, telefono, correo, tipo 
          FROM usuarios 
          ORDER BY tipo ASC, nombre ASC 
          LIMIT ? OFFSET ?";

$stmt = $conexion->prepare($query);
$stmt->bind_param("ii", $limite, $inicio);
$stmt->execute();
$resultado = $stmt->get_result();
?>