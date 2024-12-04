<?php

session_start();

if (empty($_SESSION["employeeNumber"])) {
    header("Location: login.php");
}
$autho = $_SESSION["employeeNumber"];

include 'conexion_bd.php';
// Asegúrate de conectar a la base de datos correctamente


if ($conn->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['requisitionId']) && isset($data['status'])) {
    $requisitionId = $data['requisitionId'];
    $status = $data['status'];

    // Actualiza el estado en la base de datos
    $stmt = $conexion->prepare("UPDATE requisition SET status = ?, authorizer = ? WHERE id = ?");
    $stmt->bind_param("sii", $status, $autho, $requisitionId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al actualizar']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
}
?>
