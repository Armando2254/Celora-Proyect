<?php
session_start();

if (empty($_SESSION["employeeNumber"])) {
    header("Location: login.php");
}

$hola = $_SESSION["userType"];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Título de tu página</title>
    

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/logoCAB.png" type="images/png">
    <link href="css/estilosInicio.css" rel="stylesheet" >

    <style>
        body {
            background-color: #ffffff;
        }

        .navbar{
    background-color: #50C878;
}

        .card {
            
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.05);
        }

          /* Posicionar la tarjeta en la esquina inferior izquierda */
          .image-card {
            width: 80px;
            height: 80px;
            overflow: hidden; /* Para asegurar que la imagen no se salga */
        }
        .image-card img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Para que la imagen llene la tarjeta sin deformarse */
        }
        .btn-custom {
            background-color: #50C878;
            color: white; /* Texto blanco para contraste */
        }

        .btn-custom:hover {
            background-color: #45b568; /* Color ligeramente más oscuro al pasar el mouse */
        }

    </style>
</head>
<body>


<nav class="navbar navbar-expand-lg">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="localhost/Proyecto/php/Celora.php">
      <img src="logoNoL.png" alt="Bootstrap" width="80" class="me-2">CelorA
    </a>

    <!-- Texto centrado -->
    <ul class="navbar-nav mx-auto">
      <li class="nav-item">
        <span class="navbar-text">
        <?php
        echo "Bienvenido " . $_SESSION["firstName"] . " " . $_SESSION["lastName"];
        ?>

        </span>
      </li>
    </ul>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>



    <!-- Botón "Salir" -->
    <form class="d-flex" role="search">
  <a href="controladorCS.php" class="btn btn-outline-danger">Salir</a>
</form>
  </div>
</nav>


<div class="container mt-5">
        <!-- Botón Inicio alineado a la izquierda -->
        <a href="inicio.php" class="btn btn-custom">Regresar</a>

  </div>


    <!-- Contenido principal -->
    <div class="container my-5 justify-content-center">
        <div class="row d-flex justify-content-between">
            <!-- Tarjeta 1 -->
          
            <!-- Tarjeta 2 -->
            
            <!-- Tarjeta 3 -->
            <?php if ($hola == 'u2'): ?>
            <div class="col col-lg-3">
            <a href="depa.php" class="text-decoration-none">
                <div class="card text-center">
                    <img src="camion-de-reparto.png" class="card-img-top mx-auto mt-4 mb-n5" alt="Opción 3" style="width: 100px; height: auto;">
                    <div class="card-body">
                        <h5 class="card-title">Departamentos</h5>
                        <p class="card-text">Descripción breve</p>
                    </div>
                </div>
            </a>
            </div>
            <?php endif; ?>
            <?php if ($hola == 'u2'): ?>
            <div class="col col-lg-3">
            <a href="paymentType.php" class="text-decoration-none">
                <div class="card text-center">
                    <img src="caja-del-paquete.png" class="card-img-top mx-auto mt-4" alt="Opción 3" style="width: 100px; height: auto;">
                    <div class="card-body">
                        <h5 class="card-title">Tipos de Pago</h5>
                        <p class="card-text">Descripción breve</p>
                    </div>
                </div>
            </a>
            </div>
            <?php endif; ?>
            
        </div>
    




    </div>





   
            <!-- Tarjeta 3 -->
            
       
            
 
    <!-- Footer -->
    <footer class="text-center py-3 border-top">
        <small>&copy; 2024 Empresa. Todos los derechos reservados.</small>
    </footer>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body> 
</html>
