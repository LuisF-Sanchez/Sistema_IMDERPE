<?php
// Evitar redeclaración si se incluye múltiples veces
if (!function_exists('registrar_bitacora')) {
    /**
     * Registra un evento en la tabla bitacora_sistema.
     * 
     * @param mysqli $conexion Objeto de conexión a la BD
     * @param string $accion Breve título de la acción (Ej: "Registro de Empleado")
     * @param string $descripcion Mensaje detallado sobre el movimiento realizado
     */
    function registrar_bitacora($conexion, $accion, $descripcion) {
        // Asegurarnos de que la sesión esté iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Obtener datos del usuario desde la sesión activa
        $usuario_id     = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : NULL;
        $usuario_nombre = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : 'Sistema / Desconocido';
        $usuario_rol    = isset($_SESSION['usuario_tipo']) ? $_SESSION['usuario_tipo'] : 'Desconocido';

        // Preparar la consulta para evitar inyecciones SQL
        $sql = "INSERT INTO bitacora_sistema (usuario_id, usuario_nombre, usuario_rol, accion, descripcion) 
                VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param("issss", $usuario_id, $usuario_nombre, $usuario_rol, $accion, $descripcion);
            $stmt->execute();
            $stmt->close();
        }
    }
}
?>