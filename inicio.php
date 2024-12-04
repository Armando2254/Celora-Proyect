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
      <span class="navbar-text" style="font-size: 20px;">
    <?php
    echo "Welcome " . $_SESSION["firstName"] . " " . $_SESSION["lastName"];
    ?>
</span>

      </li>
    </ul>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>



    <!-- Botón "Salir" -->
    <form class="d-flex" role="search">
  <a href="controladorCS.php" class="btn btn-outline-danger">Log out</a>
</form>
  </div>
</nav>





    <!-- Contenido principal -->
    <div class="container my-5 justify-content-center">
        <div class="row d-flex justify-content-between">
            <!-- Tarjeta 1 -->
            <div class="col col-lg-3 col-mx-auto">
            <a href="req.php" class="text-decoration-none">
                <div class="card text-center">
                    <img src="expediente.png" class="card-img-top mx-auto mt-4" alt="Opción 2" style="width: 100px; height: auto;">
                    <div class="card-body">
                        <h5 class="card-title">Requisitions</h5>
                        <p class="card-text">View and add requisitions</p>
                    </div>
                </div>
      </a>
            </div>
          
            <!-- Tarjeta 2 -->
            <?php if ($hola == 'u2'): ?>
            <div class="col col-lg-3 col-mx-auto">
            <a href="quotation.php" class="text-decoration-none">
                <div class="card text-center">
                    <img src="presupuesto.png" class="card-img-top mx-auto mt-4" alt="Opción 2" style="width: 100px; height: auto;">
                    <div class="card-body">
                        <h5 class="card-title">Quotationss</h5>
                        <p class="card-text">View and add quotations</p>
                    </div>
                </div>
      </a>
            </div>
            <?php endif; ?>

            <!-- Tarjeta 3 -->
            <?php if ($hola == 'u2'): ?>
            <div class="col col-lg-3">
            <a href="supplier.php" class="text-decoration-none">
                <div class="card text-center">
                    <img src="camion-de-reparto.png" class="card-img-top mx-auto mt-4 mb-n5" alt="Opción 3" style="width: 100px; height: auto;">
                    <div class="card-body">
                        <h5 class="card-title">Suppliers</h5>
                        <p class="card-text">View and add suppliers</p>
                    </div>
                </div>
            </a>
            </div>
            <?php endif; ?>
            <?php if ($hola == 'u2'): ?>
            <div class="col col-lg-3">
            <a href="products.php" class="text-decoration-none">
                <div class="card text-center">
                    <img src="caja-del-paquete.png" class="card-img-top mx-auto mt-4" alt="Opción 3" style="width: 100px; height: auto;">
                    <div class="card-body">
                        <h5 class="card-title">Products</h5>
                        <p class="card-text">View and add products</p>
                    </div>
                </div>
            </a>
            </div>
            <?php endif; ?>
            
        </div>
    








    <div class="container my-5">
        <div class="row d-flex justify-content-between">
            <!-- Tarjeta 1 -->
            <?php if ($hola == 'u2'): ?>
            <div class="col col-lg-3 col-mx-auto">
            <a href="purchaseOrder.php" class="text-decoration-none">
                <div class="card text-center">
                    <img src="licencias.png" class="card-img-top mx-auto mt-4" alt="Opción 1" style="width: 100px; height: auto;">
                    <div class="card-body">
                        <h5 class="card-title">Purchase Orders</h5>
                        <p class="card-text">View and add purchase orders</p>
                    </div>
                </div>
                </a>
            </div>
            <?php endif; ?>
            <!-- Tarjeta 2 -->
            <?php if ($hola == 'u2'): ?>
            <div class="col col-lg-3 col-mx-auto">
            <a href="payment.php" class="text-decoration-none">
                <div class="card text-center">
                    <img src="efectivo.png" class="card-img-top mx-auto mt-4" alt="Opción 2" style="width: 100px; height: auto;">
                    <div class="card-body">
                        <h5 class="card-title">Payments</h5>
                        <p class="card-text">View and add payments</p>
                    </div>
                </div>
      </a>
            </div>

            <?php endif; ?>
            <!-- Tarjeta 3 -->
            <div class="col col-lg-3">
            <a href="profile.php" class="text-decoration-none">
                <div class="card text-center">
                    <img src="usuario.png" class="card-img-top mx-auto my-4" alt="Opción 3" style="width: 100px; height: auto;">
                    <div class="card-body">
                        <h5 class="card-title">Profile</h5>
                        <p class="card-text">Watch your general information</p>
                    </div>
                </div>
            </a>
            </div>











            
            
            <?php if ($hola == 'u2'): ?>
            <div class="col col-lg-3">
            <a href="employee.php" class="text-decoration-none">
                <div class="card text-center">
                    <img src="feria-de-trabajo.png" class="card-img-top mx-auto mt-4" alt="Opción 3" style="width: 100px; height: auto;">
                    <div class="card-body">
                        <h5 class="card-title">Employees</h5>
                        <p class="card-text">View and add employees</p>
                    </div>
                </div>
            </a>
            </div>
            <?php endif; ?>
            
        </div>
    </div>

    <?php if ($hola == 'u2'): ?>
    <div class="d-flex">
            <a href="configuracion.php" target="_blank" class="card image-card">
                <img src="engranaje.png" alt="Imagen de ejemplo" class="card-img-top">
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
