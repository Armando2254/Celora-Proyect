<?php
include "conexion_bd.php";

// Obtiene el ID de la requisición desde la URL
$requisitionId = $_GET['id'];

// Verifica si se proporcionó un ID de requisición
if (empty($requisitionId)) {
    echo "No se ha proporcionado un ID de requisición.";
    exit();
}

// Consulta para obtener los detalles de la requisición (número y descripción)
$queryRequisition = "SELECT number, description FROM requisition WHERE id = ?";
$stmtRequisition = $conexion->prepare($queryRequisition);
$stmtRequisition->bind_param("i", $requisitionId);
$stmtRequisition->execute();
$resultRequisition = $stmtRequisition->get_result();

if ($resultRequisition->num_rows == 0) {
    echo "No se encontraron detalles para esta requisición.";
    exit();
}

$requisition = $resultRequisition->fetch_assoc();

// Consulta para obtener los productos relacionados con la requisición
$queryItems = "
    SELECT 
        p.code AS productCode,
        p.name AS productName,
        pr.quantity AS quantityRequested
    FROM 
        product_requisition pr
    JOIN 
        product p ON pr.productCode = p.code
    JOIN 
        requisition r ON pr.requisitionId = r.id
    WHERE 
        r.id = ?";
$stmtItems = $conexion->prepare($queryItems);
$stmtItems->bind_param("i", $requisitionId);
$stmtItems->execute();
$resultItems = $stmtItems->get_result();

$items = [];
while ($item = $resultItems->fetch_assoc()) {
    $items[] = [
        'productCode' => $item['productCode'],
        'productName' => $item['productName'],
        'quantityRequested' => $item['quantityRequested']
    ];
}

// Respuesta en formato HTML
echo '<h2>Requisición #' . htmlspecialchars($requisition['number']) . '</h2>';
echo '<p><strong>Descripción:</strong> ' . htmlspecialchars($requisition['description']) . '</p>';
echo '<h4>Artículos solicitados:</h4>';
echo '<ul>';
foreach ($items as $item) {
    echo '<li>' . htmlspecialchars($item['productName']) . ' (Código: ' . htmlspecialchars($item['productCode']) . ') - Cantidad: ' . htmlspecialchars($item['quantityRequested']) . '</li>';
}
echo '</ul>';
?>
