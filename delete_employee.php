<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "root", "celora");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Validar que el número de empleado esté presente en la solicitud
if (isset($_GET["employeeNumber"])) {
    $employeeNumber = intval($_GET["employeeNumber"]);

    // Eliminar el empleado de la base de datos
    $sql = "DELETE FROM employee WHERE employeeNumber = $employeeNumber";

    if ($conn->query($sql) === TRUE) {
        // Redirigir a la página principal con un mensaje de éxito
        header("Location: employee.php?message=Employee deleted successfully");
    } else {
        // Mostrar un mensaje de error en caso de fallo
        echo "Error al eliminar el empleado: " . $conn->error;
    }
} else {
    echo "No se proporcionó el número de empleado para eliminar.";
}

// Cerrar la conexión
$conn->close();
?>