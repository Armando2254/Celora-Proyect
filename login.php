

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/estilosLogin.css">
    <link rel="icon" href="images/logoCAB.png" type="images/png">
</head>
<body>

    <!-- Barra de navegación -->
    <div class="navbar">
        <a href="http://Localhost/Celora/CelorA.php">
        <img src="logoNoL.png" alt="Logo">
        </a>
        <h1>CelorA</h1>
    </div>

    <!-- Contenedor del login -->
    <div class="login-container">
        <div class="login-box">
            <form method="post" action="">
            <h2>WELCOME</h2>
            
            <?php
            include "conexion_bd.php";
            include "controladorL.php";
            ?>
            
            <label for="usuario">Employee Number</label>
            <input type="text" name="usuario" placeholder="Enter Employee Number">

            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Enter your password">

            <input name="btnIniciar" id="log" type="submit" value="LOGIN">
        </form>
        </div>
    </div>

</body>
</html>
