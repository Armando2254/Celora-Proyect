<?php
session_start();  // Asegúrate de iniciar la sesión

include('../conexion_bd.php');

if (isset($_POST['orderId'])) {
    $orderId = $_POST['orderId'];

    // Eliminar la orden de compra
    $query = "DELETE FROM purchaseOrder WHERE id = $orderId";
    if (mysqli_query($conexion, $query)) {
        $_SESSION['message'] = "Orden eliminada exitosamente.";
    } else {
        $_SESSION['message'] = "Error al eliminar la orden: " . mysqli_error($conexion);
    }

    // Redirigir a la misma página
    header("Location: ../purchaseOrder.php");
    exit();
}
?>
