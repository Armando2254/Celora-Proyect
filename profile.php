<?php
session_start();

// Verificar si hay una sesión activa
if (!isset($_SESSION['employeeNumber'])) {
    header("Location: login.php"); 
    exit();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "celora";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener datos del empleado
$employeeNumber = $_SESSION['employeeNumber'];
$sql = "SELECT e.firstName, e.lastName, e.secondLastName, d.name AS department, e.userType 
        FROM employee e
        LEFT JOIN department d ON e.departmentCode = d.code
        WHERE e.employeeNumber = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employeeNumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $employee = $result->fetch_assoc();
} else {
    $employee = null;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile</title>
    <link href="css/profile.css" rel="stylesheet">
    <link rel="icon" href="images/logoCAB.png" type="images/png">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo $employee ? $employee['firstName'] : 'Employee'; ?>!</h1>
    </header>

    <div class="btn-container">
        <a href="inicio.php" class="btn btn-custom">Inicio</a>
    </div>

    <main>
        <?php if ($employee): ?>
            <div class="profile-container">
                <h2>Employee Information</h2>

                <div class="profile-picture">
                    <img src="perfil.png" alt="Profile Picture">
                </div>

                <p><strong>Name:</strong> <?php echo $employee['firstName'] . " " . $employee['lastName'] . " " . $employee['secondLastName']; ?></p>
                <p><strong>Department:</strong> <?php echo $employee['department']; ?></p>
                <p><strong>User Type:</strong> <?php echo $employee['userType']; ?></p>
            </div>
        <?php else: ?>
            <div class="profile-container">
                <h2>No Employee Data</h2>
                <p>We couldn't find information for this employee.</p>
            </div>
        <?php endif; ?>
    </main>

    <footer id="footer">
        <div class="footer-container">
            
            <a href="logout.php" class="footer-link">Log out</a>
            <p>&copy; 2024 CelorA. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>