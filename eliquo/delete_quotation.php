<?php
include '../conexion_bd.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM quotation WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $id);
    if ($stmt->execute()) {
        echo "Cotización eliminada correctamente.";
    } else {
        echo "Error al eliminar la cotización.";
    }
    $stmt->close();
} else {
    echo "ID de cotización no proporcionado.";
}
?>
