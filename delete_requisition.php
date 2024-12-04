<?php
// Conexión a la base de datos
include 'conexion_bd.php';

// Verificar si se recibió el parámetro ID
if (isset($_POST['id'])) {
    $requisitionId = intval($_POST['id']);

    // Iniciar transacción
    $conexion->begin_transaction();

    try {
        // Eliminar los productos relacionados con la requisición
        $deleteProductsQuery = "DELETE FROM product_requisition WHERE requisitionId = ?";
        $stmtProducts = $conexion->prepare($deleteProductsQuery);
        $stmtProducts->bind_param("i", $requisitionId);
        $stmtProducts->execute();

        // Eliminar entradas en la tabla report
        $deleteReportQuery = "DELETE FROM report WHERE requisitionId = ?";
        $stmtReport = $conexion->prepare($deleteReportQuery);
        $stmtReport->bind_param("i", $requisitionId);
        $stmtReport->execute();

        // Eliminar la cotizació

        // Confirmar transacción
        $conexion->commit();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $conexion->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>

