<?php
include '../conexion_bd.php';

$query = "SELECT id, legalName FROM supplier";
$result = $conexion->query($query);

$suppliers = [];
while ($row = $result->fetch_assoc()) {
    $suppliers[] = $row;
}

echo json_encode($suppliers);
?>
