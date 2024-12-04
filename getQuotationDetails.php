<?php
// Conexión a la base de datos
include 'conexion_bd.php';

// Verificar si se recibió el parámetro ID
if (isset($_GET['id'])) {
    $quotationId = $_GET['id'];

    // Consultar la información de la cotización
    $queryQuotation = "
        SELECT 
            q.id AS quotation_id,
            q.issueDate AS quotation_date,
            q.total AS quotation_total,
            q.subtotal AS quotation_subtotal,
            s.legalName AS supplier_name
        FROM quotation q
        INNER JOIN supplier s ON q.supplierId = s.id
        WHERE q.id = ?";
    $stmtQuotation = $conexion->prepare($queryQuotation);
    $stmtQuotation->bind_param("s", $quotationId);
    $stmtQuotation->execute();
    $resultQuotation = $stmtQuotation->get_result();

    if ($resultQuotation->num_rows > 0) {
        $quotation = $resultQuotation->fetch_assoc();
        echo "<h2>Cotización #{$quotation['quotation_id']}</h2>";
        echo "<p>Fecha de emisión: " . htmlspecialchars($quotation['quotation_date']) . "</p>";
        echo "<p>Proveedor: " . htmlspecialchars($quotation['supplier_name']) . "</p>";
        echo "<p>Subtotal: $" . number_format($quotation['quotation_subtotal'], 2) . "</p>";
        echo "<p>Total: $" . number_format($quotation['quotation_total'], 2) . "</p>";
    } else {
        echo "No se encontró información para la cotización.";
        exit;
    }

    $stmtQuotation->close();

    // Consultar los productos relacionados con la cotización
    $queryProducts = "
        SELECT 
            p.name AS product_name,
            p.price AS product_price,
            pr.quantity AS product_quantity
        FROM product_requisition pr
        INNER JOIN product p ON pr.productCode = p.code
        INNER JOIN requisition r ON pr.requisitionId = r.id
        INNER JOIN report rp ON rp.requisitionId = r.id
        WHERE rp.quotationId = ?";
    $stmtProducts = $conexion->prepare($queryProducts);
    $stmtProducts->bind_param("s", $quotationId);
    $stmtProducts->execute();
    $resultProducts = $stmtProducts->get_result();

    // Generar tabla HTML
    if ($resultProducts->num_rows > 0) {
        echo "<table class='table table-bordered'>";
        echo "<thead><tr><th>Producto</th><th>Precio</th><th>Cantidad</th></tr></thead>";
        echo "<tbody>";

        while ($row = $resultProducts->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
            echo "<td>$" . number_format($row['product_price'], 2) . "</td>";
            echo "<td>" . htmlspecialchars($row['product_quantity']) . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No hay productos asociados con esta cotización.</p>";
    }

    $stmtProducts->close();
} else {
    echo "ID de cotización no proporcionado.";
}

$conexion->close();
?>
