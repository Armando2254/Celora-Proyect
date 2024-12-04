<?php
include '../conexion_bd.php';

if ($conexion->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conexion->query("SELECT * FROM requisition WHERE id = $id");
    echo json_encode($result->fetch_assoc());
} else {
    $result = $conexion->query("SELECT id, description FROM requisition");
    $requisitions = [];
    while ($row = $result->fetch_assoc()) {
        $requisitions[] = $row;
    }
    echo json_encode($requisitions);
}
?>
