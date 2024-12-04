<?php
include '../conexion_bd.php';
$query = "SELECT code, name FROM product";
$result = $conexion->query($query);
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
echo json_encode($products);
?>