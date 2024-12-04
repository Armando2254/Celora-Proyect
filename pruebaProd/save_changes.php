<?php
include '../conexion_bd.php';
$data = json_decode(file_get_contents("php://input"), true);

$requisitionId = $data['requisitionId'];
$products = $data['products'];

// Obtener productos actuales
$currentProducts = [];
$query = "SELECT productCode FROM product_requisition WHERE requisitionId = $requisitionId";
$result = $conexion->query($query);
while ($row = $result->fetch_assoc()) {
    $currentProducts[] = $row['productCode'];
}





// Actualizar o insertar productos
foreach ($products as $product) {
    $productCode = $product['productCode'];
    $quantity = $product['quantity'];


    if (empty($productCode) || $quantity <= 0) {
        echo json_encode([
            "success" => false,
            "message" => "Los datos del producto son inválidos. Asegúrate de seleccionar un producto y que la cantidad sea mayor a cero."
        ]);
        exit;
    }

    if (in_array($productCode, $currentProducts)) {
        $query = "UPDATE product_requisition SET quantity = $quantity 
                  WHERE requisitionId = $requisitionId AND productCode = $productCode";
        $conexion->query($query);
    } else {
        $query = "INSERT INTO product_requisition (productCode, requisitionId, quantity) 
                  VALUES ($productCode, $requisitionId, $quantity)";
        $conexion->query($query);
    }
}

// Eliminar productos extra


$productCodes = array_column($products, 'productCode');
$productCodesString = implode(",", $productCodes);


if (!empty($productCodes)) {
    $productCodesString = implode(",", $productCodes);
    $conexion->query("DELETE FROM product_requisition 
                  WHERE requisitionId = $requisitionId 
                  AND productCode NOT IN ($productCodesString)");
} else {
    $conexion->query("DELETE FROM product_requisition 
                  WHERE requisitionId = $requisitionId");
}


echo json_encode(['success' => true]); 
?>