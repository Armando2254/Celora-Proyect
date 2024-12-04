<?php
// obtener_requisiciones.php

// Conexión a la base de datos (ajusta según tus credenciales)
$mysqli = new mysqli('localhost', 'root', 'root', 'celora3');

// Verifica si hubo un error de conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Consulta para obtener las requisiciones
$query = "SELECT id, description FROM requisition";
$result = $mysqli->query($query);

// Verifica si se obtuvieron resultados
if ($result->num_rows > 0) {
    $requisiciones = array();
    while ($row = $result->fetch_assoc()) {
        $requisiciones[] = $row;
    }
    
    // Devuelve las requisiciones como JSON
    echo json_encode($requisiciones);
} else {
    // Si no se encuentran requisiciones
    echo json_encode([]);
}

// Cierra la conexión
$mysqli->close();
?>
