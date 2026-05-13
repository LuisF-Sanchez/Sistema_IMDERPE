<?php
require_once 'conexion.php';

$response = ['success' => false, 'id' => null, 'nombre' => '', 'cedula' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];

    $sql = "INSERT INTO representantes (cedula, nombre, apellido, telefono, direccion) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssss", $cedula, $nombre, $apellido, $telefono, $direccion);

    if ($stmt->execute()) {
        $nuevo_id = $conexion->insert_id;
        
        $response['success'] = true;
        $response['id'] = $nuevo_id;
        $response['nombre'] = $nombre . " " . $apellido;
        $response['cedula'] = $cedula;
    }
    
    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);
$conexion->close();