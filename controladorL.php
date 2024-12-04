<?php
session_start();

include 'conexion_bd.php';
if (!empty($_POST["btnIniciar"])) {
    if (!empty($_POST["usuario"]) and !empty($_POST["password"])) {
        $usuario = $_POST["usuario"];
        $password = $_POST["password"];

        // Consulta para obtener el usuario
        $sql = $conexion->query("SELECT * FROM employee WHERE employeeNumber='$usuario'");
        
        if ($datos = $sql->fetch_object()) {
            // Almacena los datos del usuario en la sesión
            $_SESSION["employeeNumber"] = $datos->employeeNumber;
            $_SESSION["firstName"] = $datos->firstName;
            $_SESSION["lastName"] = $datos->lastName;
            $_SESSION["userType"] = $datos->userType;
            $_SESSION["departmentCode"] = $datos->departmentCode;

            // Verifica la contraseña con el método actual (por ejemplo, SHA-256)
            // Aquí se usa la función hash() para simular que tienes SHA-256 en tu base de datos
            if (hash('sha256', $password) === $datos->password) {
                
                // Si las contraseñas coinciden, realiza el rehash de la contraseña
                $password_encrypted = password_hash($password, PASSWORD_BCRYPT);
                
                // Actualiza la contraseña en la base de datos con el nuevo hash
              
                
                // Redirige al usuario al inicio
                header("Location: inicio.php");
            } else {
                // Contraseña incorrecta
                echo "<div>Acceso Denegado</div>";
            }
        } else {
            // Usuario no encontrado
            echo "<div>Acceso Denegado</div>";
        }
    } else {
        // Campos vacíos
        echo "LOS CAMPOS ESTAN VACIOS";
    }
}
?>