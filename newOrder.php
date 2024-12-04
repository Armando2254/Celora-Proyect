<?php
include 'conexion_bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requiredDate = $_POST['requiredDate'];
    $requisitionId = $_POST['requisitionId'];

    // Llamar al procedimiento almacenado
    $stmt = $conexion->prepare("CALL datosReq(?, @cantidad, @total, @subtotal)");
    $stmt->bind_param("i", $requisitionId);
    $stmt->execute();

    // Obtener las variables del procedimiento
    $result = $conexion->query("SELECT @cantidad AS cantidad, @total AS total, @subtotal AS subtotal");
    $data = $result->fetch_assoc();

    $cantidad = $data['cantidad'];
    $total = $data['total'];
    $subtotal = $data['subtotal'];

    // Insertar en la tabla de órdenes de compra
    $insertOrder = $conexion->prepare("INSERT INTO purchaseOrder (requiredQuantity, requiredDate, total, subtotal) VALUES (?, ?, ?, ?)");
    $insertOrder->bind_param("isdd", $cantidad, $requiredDate, $total, $subtotal);
    $insertOrder->execute();

    // Actualizar reporte
    $orderId = $conexion->insert_id;
    $updateReport = $conexion->prepare("UPDATE report SET purchaseOrderId = ? WHERE requisitionId = ?");
    $updateReport->bind_param("ii", $orderId, $requisitionId);
    $updateReport->execute();

    header("Location: purchaseOrder.php");
}
?>
