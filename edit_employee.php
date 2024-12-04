<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Conexión a la base de datos
    $conn = new mysqli("localhost", "root", "root", "celora");
    
    if ($conn->connect_error) {
        // Redirigir con un mensaje de error de conexión
        header("Location: employee.php?status=error&message=" . urlencode("Database connection failed: " . $conn->connect_error));
        exit;
    }

    // Recibir datos
    $employeeNumber = $_POST["employeeNumber"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $secondLastName = $_POST["secondLastName"];

    // Depuración: muestra los datos recibidos (solo para pruebas)
    // var_dump($_POST); // Puedes comentar o eliminar esto en producción.

    // Consulta de actualización
    $stmt = $conn->prepare("UPDATE employee SET firstName = ?, lastName = ?, secondLastName = ? WHERE employeeNumber = ?");
    
    if ($stmt === false) {
        // Redirigir con un mensaje de error al preparar la consulta
        header("Location: employee.php?status=error&message=" . urlencode("Error preparing query: " . $conn->error));
        exit;
    }

    // Bind de parámetros
    $stmt->bind_param("sssi", $firstName, $lastName, $secondLastName, $employeeNumber);

    // Ejecutar la consulta
    if (!$stmt->execute()) {
        // Redirigir con un mensaje de error al ejecutar la consulta
        header("Location: employee.php?status=error&message=" . urlencode("Failed to update employee: " . $stmt->error));
    } else {
        // Redirigir con un mensaje de éxito
        header("Location: employee.php?status=success&message=" . urlencode("Employee updated successfully."));
    }

    $stmt->close();
    $conn->close();
} else {
    // Si no se accede por POST, redirigir con un mensaje de error
    header("Location: employee.php?status=error&message=" . urlencode("Invalid request method."));
    exit;
}
