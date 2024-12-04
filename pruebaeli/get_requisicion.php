<?php
include '../conexion_bd.php';

$id = $_GET['id'];

// Obtener datos de la requisición
$query = "SELECT * FROM requisition WHERE id = $id";
$result = mysqli_query($conexion, $query);
$requisition = mysqli_fetch_assoc($result);

// Obtener productos asociados
$query = "
    SELECT p.name, pr.quantity
    FROM product_requisition pr
    JOIN product p ON pr.productCode = p.code
    WHERE pr.requisitionId = $id
";
$result = mysqli_query($conexion, $query);

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

echo json_encode(['id' => $requisition['id'], 'description' => $requisition['description'], 'products' => $products]);
?>
