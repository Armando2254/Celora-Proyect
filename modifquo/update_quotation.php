<?php
include '../conexion_bd.php';

if (isset($_POST['id'], $_POST['issueDate'], $_POST['total'], $_POST['subtotal'], $_POST['supplierId'])) {
    $id = $_POST['id'];
    $issueDate = $_POST['issueDate'];
    $total = $_POST['total'];
    $subtotal = $_POST['subtotal'];
    $supplierId = $_POST['supplierId'];

    $query = "UPDATE quotation SET issueDate = ?, total = ?, subtotal = ?, supplierId = ? WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sddis", $issueDate, $total, $subtotal, $supplierId, $id);

    if ($stmt->execute()) {
        echo "Cotización actualizada correctamente.";
    } else {
        echo "Error al actualizar la cotización.";
    }
    $stmt->close();
    
}
?>
