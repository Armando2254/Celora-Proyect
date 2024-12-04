<?php
include('../conexion_bd.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $paymentId = $_POST['paymentId'];

    // Eliminar el pago
    $deleteQuery = "DELETE FROM payment WHERE id = ?";
    $stmt = mysqli_prepare($conexion, $deleteQuery);
    mysqli_stmt_bind_param($stmt, "i", $paymentId);
    mysqli_stmt_execute($stmt);

    // Redirigir a la página de pagos
    header("Location: ../payment.php");
    exit();
}
?>
