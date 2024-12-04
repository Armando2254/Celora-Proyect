<?php
include '../conexion_bd.php';

$query = "SELECT id FROM quotation";
$result = $conexion->query($query);

$quotations = [];
while ($row = $result->fetch_assoc()) {
    $quotations[] = $row;
}

echo json_encode($quotations);
?>
