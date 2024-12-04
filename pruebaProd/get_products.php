<?php
include '../conexion_bd.php';
$requisitionId = $_GET['requisitionId'];
$query = "SELECT pr.productCode, p.name, pr.quantity 
          FROM product_requisition pr 
          JOIN product p ON pr.productCode = p.code 
          WHERE pr.requisitionId = $requisitionId";
$result = $conexion->query($query);
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
echo json_encode($products);
?>
