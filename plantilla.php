<?php
session_start();

if (empty($_SESSION["employeeNumber"])) {
    header("Location: login.php");
}

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
    </style>
</head>
<body>


<nav class="navbar navbar-expand-lg">
  <div class="container-fluid d-flex align-items-center justify-content-between">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="logoNoL.png" alt="Bootstrap" width="90" class="me-2 mt-n1 mb-n1"></a>

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


















<div class="container form-container">
    <div class="form-box">
        <h2>Formulario de Requisición</h2>

        <!-- Formulario -->
        <form method="POST" action="submitForm.php" id="requisitionForm">
            <!-- Campo de Folio -->
            <div class="mb-3">
                <label for="folio" class="form-label">Folio</label>
                <input type="text" id="folio" name="folio" class="form-control" value="<?php 
                include "conexion_bd.php";
                    $consulta_folio = "SELECT MAX(id) AS max_folio FROM requisition";
                    $result = $conexion->query($consulta_folio);
                    $row = $result->fetch_assoc();
                    $nuevo_folio = $row['max_folio'] + 1;
                    echo $nuevo_folio;
                ?>" readonly>
            </div>

            <!-- Dinámico: Campos para Cantidad y Producto -->
            <div id="product-container">
                <div class="product-group mb-3">
                    <label for="cantidad" class="form-label">Cantidad</label>
                    <input type="number" name="cantidad[]" class="form-control mb-2" placeholder="Ingresa la cantidad" required>

                    <label for="numero-producto" class="form-label">Número de Producto</label>
                    <select name="numero_producto[]" class="form-select" required>
                        <?php
                        $consulta_productos = "SELECT code, name FROM product";
                        $result = $conexion->query($consulta_productos);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['code'] . "'>" . $row['name'] . "</option>";
                        }
                        ?>
                    </select>
                    <button type="button" class="btn btn-danger mt-2 remove-product">Eliminar</button>
                </div>
            </div>

            <button type="button" class="btn btn-secondary mb-3" id="add-product">Agregar Producto</button>

            <!-- Menú desplegable para Nombre del Solicitante -->
            <div class="mb-3">
                <label for="nombre-solicitante" class="form-label">Nombre del Solicitante</label>
                <select id="nombre-solicitante" name="numero_solicitante" class="form-select" required>
                    <?php
                    $consulta_empleados = "SELECT employeeNumber, firstName, lastName FROM employee";
                    $result = $conexion->query($consulta_empleados);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['employeeNumber'] . "'>" . $row['employeeNumber'] . " - " . $row['firstName'] . " " . $row['lastName'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Campo de fecha -->
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" id="fecha" name="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
            </div>

            <!-- Campo de descripción -->
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea id="descripcion" name="descripcion" class="form-control" rows="4" placeholder="Describe la requisición..."></textarea>
            </div>

            <!-- Botón de envío -->
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('add-product').addEventListener('click', function() {
        const container = document.getElementById('product-container');
        const newProductGroup = document.createElement('div');
        newProductGroup.classList.add('product-group', 'mb-3');
        newProductGroup.innerHTML = `
            <label for="cantidad" class="form-label">Cantidad</label>
            <input type="number" name="cantidad[]" class="form-control mb-2" placeholder="Ingresa la cantidad" required>
            <label for="numero-producto" class="form-label">Número de Producto</label>
            <select name="numero_producto[]" class="form-select" required>
                <?php
                $consulta_productos = "SELECT code, name FROM product";
                $result = $conexion->query($consulta_productos);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['code'] . "'>" . $row['name'] . "</option>";
                }
                ?>
            </select>
            <button type="button" class="btn btn-danger mt-2 remove-product">Eliminar</button>
        `;
        container.appendChild(newProductGroup);
    });

    document.getElementById('product-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product')) {
            e.target.closest('.product-group').remove();
        }
    });
</script>











    <!-- Footer -->
    <footer class="text-center py-3 border-top">
        <small>&copy; 2024 Empresa. Todos los derechos reservados.</small>
    </footer>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body> 
</html>
