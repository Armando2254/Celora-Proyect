<?php
include 'conexion_bd.php';

// Validar datos recibidos del formulario
if (!isset($_POST['id'], $_POST['issueDate'], $_POST['total'], $_POST['subtotal'], $_POST['supplierId'], $_POST['requisitionId'])) {
    die("Error: Datos incompletos recibidos.");
}

// Recibir los datos del formulario
$id = $_POST['id'];
$issueDate = $_POST['issueDate'];
$total = $_POST['total'];
$subtotal = $_POST['subtotal'];
$supplierId = $_POST['supplierId'];
$requisitionId = $_POST['requisitionId'];

// Verificar que el ID no exista previamente
$checkIdQuery = $conexion->prepare("SELECT id FROM quotation WHERE id = ?");
$checkIdQuery->bind_param("s", $id);
$checkIdQuery->execute();
$checkIdQuery->store_result();

if ($checkIdQuery->num_rows > 0) {
    die("Error: El ID proporcionado ya existe. Por favor, elija otro.");
}
$checkIdQuery->close();

// Insertar en la tabla cotización
$stmt = $conexion->prepare("INSERT INTO quotation (id, issueDate, total, subtotal, supplierId) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssds", $id, $issueDate, $total, $subtotal, $supplierId);

if ($stmt->execute()) {
    // Verificar que los datos de requisición están disponibles
    if ($requisitionId) {
        // Actualizar la tabla report con la nueva cotización
        $updateReport = $conexion->prepare("UPDATE report SET quotationId = ? WHERE requisitionId = ?");
        $updateReport->bind_param("si", $id, $requisitionId);

        if ($updateReport->execute() && $updateReport->affected_rows > 0) {
            echo "La cotización se ha guardado y la tabla report ha sido actualizada.";
        } else {
            echo "Advertencia: No se encontró ninguna requisición para actualizar.";
        }
        $updateReport->close();
    } else {
        echo "No se seleccionó una requisición válida.";
    }
} else {
    die("Error al insertar la cotización: " . $stmt->error);
}

$stmt->close();

// Redirigir a la página de cotizaciones
header("Location: quotation.php");
exit();
?>
