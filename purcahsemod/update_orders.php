<?php
include('../conexion_bd.php');

if (isset($_POST['orderId']) && isset($_POST['requiredDate'])) {
    $orderId = $_POST['orderId'];
    $requiredDate = $_POST['requiredDate'];

    // Actualizamos los datos de la orden
    $query = "UPDATE purchaseOrder SET requiredDate = '$requiredDate' WHERE id = $orderId";
    if (mysqli_query($conexion, $query)) {
        echo "Orden actualizada exitosamente.";
    } else {
        echo "Error al actualizar la orden: " . mysqli_error($conexion);
    }

    // Redirigimos a la misma página
    header("Location: ../purchaseOrder.php");
    exit();
}
?>
