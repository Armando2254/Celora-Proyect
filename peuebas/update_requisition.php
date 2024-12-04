<?php
include '../conexion_bd.php';

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = intval($_POST['id']);
$date = $_POST['date'];
$description = $_POST['description'];
$status = $_POST['status'];

$query = "UPDATE requisition SET date = '$date', description = '$description', status = '$status' WHERE id = $id";
$result = $conexion->query($query);

echo json_encode(['success' => $result]);
?>
