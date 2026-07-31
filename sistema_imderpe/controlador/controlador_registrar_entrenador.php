<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'conexion.php';
require_once 'registrar_bitacora.php';

if (!empty($_POST["btn_registrar"])) {

    if (!empty($_POST["cedula"]) && !empty($_POST["nombre"]) && !empty($_POST["apellido"]) && !empty($_POST["disciplina_id"])) {
        
        $cedula = $_POST["cedula"];
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $disciplina_id = $_POST["disciplina_id"];
        $telefono = $_POST["telefono"];
        $correo = $_POST["correo"];
        $estado = $_POST["estado"];

        $check_cedula = $conexion->query("SELECT id FROM entrenadores WHERE cedula = '$cedula'");

        if ($check_cedula->num_rows > 0) {

            header("Location: ../vista/ver_entrenadores.php?error_duplicado=1");
            exit();
        } else {

            $sql = $conexion->prepare("INSERT INTO entrenadores (cedula, nombre, apellido, disciplina_id, telefono, correo, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $sql->bind_param("sssisss", $cedula, $nombre, $apellido, $disciplina_id, $telefono, $correo, $estado);

            if ($sql->execute()) {
                $rol = ucfirst($_SESSION['usuario_tipo'] ?? 'Usuario');
                $nombre_user = $_SESSION['usuario_nombre'] ?? 'Desconocido';
                $cedula_txt = !empty($cedula) ? " (C.I. {$cedula})" : "";
                $descripcion = "El {$rol} {$nombre_user} ha registrado al entrenador {$nombre} {$apellido}{$cedula_txt}.";
                registrar_bitacora($conexion, "Registro de Entrenador", $descripcion);

                header("Location: ../vista/ver_entrenadores.php?registro_exito=1");
                exit();
            } else {

                header("Location: ../vista/ver_entrenadores.php?error=1");
                exit();
            }
            $sql->close();
        }
    } else {

        header("Location: ../vista/ver_entrenadores.php?error_campos=1");
        exit();
    }
}

$conexion->close();
?>