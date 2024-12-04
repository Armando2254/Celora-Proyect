<?php
session_start();

if (empty($_SESSION["employeeNumber"])) {
    header("Location: login.php");
    exit(); // Asegúrate de detener la ejecución después de redirigir
}

$hola = $_SESSION["employeeNumber"];

include 'conexion_bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $folio = $_POST['folio'] ?? 0;
    $descripcion = $_POST['descripcion'] ?? '';
    $fecha = $_POST['fecha'] ?? date('Y-m-d');
    $productos = $_POST['productos'] ?? [];
    $cantidades = $_POST['cantidades'] ?? [];
    $status = 'Pending';

    if (empty($productos) || empty($cantidades) || count($productos) !== count($cantidades)) {
        die("Error: Productos y cantidades no coinciden o están vacíos.");
    }

    try {
        // Iniciar transacción
        $conexion->begin_transaction();

        // Insertar en la tabla requisition
        $requiredQuantity = array_sum($cantidades);
        $insertRequisition = $conexion->prepare(
            "INSERT INTO requisition (id, date, description, requiredQuantity, requester, status) VALUES (?, ?, ?, ?, ?, ?)"
        );
        $insertRequisition->bind_param("issiis", $folio, $fecha, $descripcion, $requiredQuantity, $hola, $status);

        if (!$insertRequisition->execute()) {
            throw new Exception("Error al insertar en requisition: " . $insertRequisition->error);
        }

        // Insertar en la tabla product_requisition
        $insertProductRequisition = $conexion->prepare(
            "INSERT INTO product_requisition (productCode, requisitionId, quantity) VALUES (?, ?, ?)"
        );

        foreach ($productos as $index => $productCode) {
            $quantity = $cantidades[$index];
            $insertProductRequisition->bind_param("iii", $productCode, $folio, $quantity);

            if (!$insertProductRequisition->execute()) {
                throw new Exception("Error al insertar en product_requisition: " . $insertProductRequisition->error);
            }
        }

        // Confirmar transacción
        $conexion->commit();

        echo "Requisición creada correctamente.";
        header("Location: req.php");
    } catch (Exception $e) {
        // Revertir cambios en caso de error
        $conexion->rollback();
        die("Error: " . $e->getMessage());
    }
}
?>
