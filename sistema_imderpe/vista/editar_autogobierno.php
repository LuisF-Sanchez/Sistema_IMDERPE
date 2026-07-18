<?php
session_start();
if (!isset($_SESSION['usuario_nombre'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../controlador/conexion.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ver_autogobierno.php");
    exit();
}

$id_responsable = $_GET['id'];

$stmt = $conexion->prepare("SELECT nombre, apellido, cedula, telefono, correo, direccion, comuna FROM autogobierno WHERE id = ?");
$stmt->bind_param("i", $id_responsable);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    $stmt->close();
    $conexion->close();
    header("Location: ver_autogobierno.php");
    exit();
}

$responsable = $resultado->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Responsable - IMDERPE</title>
    <link rel="stylesheet" href="../estilo/style18.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="glass-form">

            <img src="../estilo/logo.png" alt="IMDERPE" class="logo-form">
            
            <h2 class="form-title">Editar Responsable</h2>
            
            <form action="../controlador/controlador_editar_autogobierno.php" method="POST">
                
                <input type="hidden" name="id" value="<?php echo $id_responsable; ?>">

                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" name="cedula" placeholder="Cédula de Identidad" required autocomplete="off" value="<?php echo htmlspecialchars($responsable['cedula']); ?>">
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nombre" placeholder="Nombres" required autocomplete="off" value="<?php echo htmlspecialchars($responsable['nombre']); ?>">
                </div>

                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="apellido" placeholder="Apellidos" required autocomplete="off" value="<?php echo htmlspecialchars($responsable['apellido']); ?>">
                </div>

                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="telefono" placeholder="Número de Teléfono" required autocomplete="off" value="<?php echo htmlspecialchars($responsable['telefono']); ?>">
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="correo" placeholder="Correo Electrónico" required autocomplete="off" value="<?php echo htmlspecialchars($responsable['correo']); ?>">
                </div>

                <div class="input-group">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="direccion" placeholder="Dirección Sala de Autogobierno" required autocomplete="off" value="<?php echo htmlspecialchars($responsable['direccion']); ?>">
                </div>

                <div class="input-group">
                    <i class="fas fa-users"></i>
                    <input type="text" name="comuna" placeholder="Comuna" required autocomplete="off" value="<?php echo htmlspecialchars($responsable['comuna']); ?>">
                </div>

                <button type="submit" name="btn_editar" class="btn-register">
                    Guardar Cambios
                </button>

                <a href="ver_autogobierno.php" class="btn-cancel">Cancelar y Volver</a>
            </form>
        </div>
    </div>
</body>
</html>
<?php
$conexion->close();
?>