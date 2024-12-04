<?php
// Conexión a la base de datos
include 'conexion_bd.php';

// Verificar si se recibió el parámetro ID
if (isset($_GET['id'])) {
    $requisitionId = intval($_GET['id']);

    // Consultar los datos de la requisición
    $query = "SELECT description FROM requisition WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $requisitionId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si se encuentra la requisición, devolver los datos en formato JSON
    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            'description' => $row['description'],
            // Agregar más campos si es necesario
        ]);
    } else {
        echo json_encode(['error' => 'Requisición no encontrada']);
    }
} else {
    echo json_encode(['error' => 'ID no proporcionado']);
}
?>
