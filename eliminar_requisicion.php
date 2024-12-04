<?php
// eliminar_requisicion.php
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];

    // Conexión a la base de datos
    $mysqli = new mysqli('localhost', 'root', 'root', 'celora3');
    if ($mysqli->connect_error) {
        die("Error de conexión: " . $mysqli->connect_error);
    }

    // Verificar si la requisición existe
    $query = "SELECT * FROM requisition WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Eliminar la requisición
        $deleteQuery = "DELETE FROM requisition WHERE id = ?";
        $stmtDelete = $mysqli->prepare($deleteQuery);
        $stmtDelete->bind_param('i', $id);
        $stmtDelete->execute();

        // Confirmación de eliminación
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $mysqli->close();
}

?>
