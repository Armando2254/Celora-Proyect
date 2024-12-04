<?php
include 'conexion_bd.php'; // Archivo con la conexión a la base de datos

$query = "SELECT MAX(id) AS max_id FROM requisition";
$result = $conexion->query($query);
$row = $result->fetch_assoc();
$folio = isset($row['max_id']) ? $row['max_id'] + 1 : 1;

echo $folio;
?>
