<?php
include 'conexion_bd.php';

$reference = $_POST['reference'];
$concept = $_POST['concept'];
$paymentType = $_POST['paymentType'];
$purchaseOrderId = $_POST['purchaseOrderId'];

$query = "INSERT INTO payment (reference, concept, paymentTypeCode, purchaseOrderId) 
          VALUES ('$reference', '$concept', '$paymentType', $purchaseOrderId)";
if ($conexion->query($query) === TRUE) {
    header("Location: payment.php");
} else {
    echo "Error: " . $conexion->error;
}
?>
