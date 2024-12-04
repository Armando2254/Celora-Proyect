<?php

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'], $data['status'])) {
    $id = $data['id'];
    $status = $data['status'];

    $conn = new mysqli("localhost", "root", "root", "celora3");

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE requisition SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode('success');
    } else {
        echo json_encode('failure');
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode('invalid');
}
?>
