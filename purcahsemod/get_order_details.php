<?php
include('../conexion_bd.php');

if (isset($_GET['orderId'])) {
    $orderId = $_GET['orderId'];

    $query = "SELECT po.requiredDate, po.requiredQuantity, po.total, po.subtotal
              FROM purchaseOrder po
              WHERE po.id = $orderId";
    $result = mysqli_query($conexion, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row);
    }
}
?>
