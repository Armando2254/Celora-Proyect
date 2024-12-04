<?php
include '../conexion_bd.php';
$query = "SELECT id, description FROM requisition";
$result = $conexion->query($query);
$requisitions = [];
while ($row = $result->fetch_assoc()) {
    $requisitions[] = $row;
}
echo json_encode($requisitions);
?>
