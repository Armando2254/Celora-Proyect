<?php
include('../conexion_bd.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentId = $_POST['paymentId'];
    $newReference = $_POST['newReference'];
    $newConcept = $_POST['newConcept'];
    $paymentType = $_POST['paymentType'];
    $purchaseOrder = $_POST['purchaseOrder'];

    // Actualizar el pago
    $updateQuery = "UPDATE payment SET reference = ?, concept = ?, paymentTypeCode = ?, purchaseOrderId = ? WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $updateQuery);
    mysqli_stmt_bind_param($stmt, "sssis", $newReference, $newConcept, $paymentType, $purchaseOrder, $paymentId);
    mysqli_stmt_execute($stmt);

    // Redirigir a la página de pagos
    header("Location: ../payment.php");
    exit();
}
?>
