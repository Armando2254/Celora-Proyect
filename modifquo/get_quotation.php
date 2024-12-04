<?php
include '../conexion_bd.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT id, issueDate, total, subtotal, supplierId FROM quotation WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $quotation = $result->fetch_assoc();
    echo json_encode($quotation);
    $stmt->close();
}
?>
