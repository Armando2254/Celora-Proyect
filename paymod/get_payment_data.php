<?php
include('../conexion_bd.php');

if (isset($_POST['id'])) {
    $paymentId = $_POST['id'];
    
    // Consulta para obtener los datos del pago seleccionado
    $query = "SELECT reference, concept, paymentTypeCode, purchaseOrderId FROM payment WHERE id = $paymentId";
    $result = mysqli_query($conexion, $query);
    
    if ($result) {
        $paymentData = mysqli_fetch_assoc($result);
        echo json_encode($paymentData);
    } else {
        echo json_encode(['error' => 'No se encontraron datos para este pago']);
    }
} else {
    echo json_encode(['error' => 'ID de pago no proporcionado']);
}
?>
