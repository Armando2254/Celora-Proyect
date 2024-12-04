<?php

session_start();

if (empty($_SESSION["employeeNumber"])) {
    header("Location: login.php");
}
// Conexión a la base de datos
include 'conexion_bd.php';

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener órdenes de compra
$result = $conexion->query("SELECT * FROM purchaseOrder ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Título de tu página</title>
    
    <link rel="icon" href="images/logoCAB.png" type="images/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/btn.css" rel="stylesheet" >
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




<div class="container mt-5">
        <!-- Botón Inicio alineado a la izquierda -->
        <a href="inicio.php" class="btn btn-custom">Home</a>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modifyModal">Modify</button>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
        
    </div>













    



    




<div class="container mt-5">
    <h1 class="mb-4">Purchase Orders</h1>
    <button class="btn btn-success mt-4" data-bs-toggle="modal" data-bs-target="#newOrderModal">Add Purchase Order</button>
    <div class="row">
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Order #<?php echo $row['id']; ?></h5>
                        <p class="card-text">Required Date: <?php echo $row['requiredDate']; ?></p>
                        <p class="card-text">Total: $<?php echo $row['total']; ?></p>
                        <button class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#orderModal<?php echo $row['id']; ?>">View Details</button>
                    </div>
                </div>
            </div>

            <!-- Modal Detalles -->
            <div class="modal fade" id="orderModal<?php echo $row['id']; ?>" tabindex="-1"
                 aria-labelledby="orderModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Order Details #<?php echo $row['id']; ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php
                            // Obtener información relacionada con la orden
                            $orderId = $row['id'];
                            $reportQuery = $conexion->query("SELECT * FROM report WHERE purchaseOrderId = $orderId");
                            while ($report = $reportQuery->fetch_assoc()) {
                                $requisitionId = $report['requisitionId'];
                                $quotationId = $report['quotationId'];

                                // Obtener cotización
                                $quotationQuery = $conexion->query("SELECT * FROM quotation WHERE id = '$quotationId'");
                                $quotation = $quotationQuery->fetch_assoc();

                                // Obtener productos
                                $productQuery = $conexion->query("
                                    SELECT p.name, pr.quantity FROM product p 
                                    JOIN product_requisition pr ON p.code = pr.productCode 
                                    WHERE pr.requisitionId = $requisitionId");
                                ?>
                                <p><strong>Supplier:</strong> <?php echo $quotation['supplierId']; ?></p>
                                <p><strong>Total:</strong> $<?php echo $quotation['total']; ?></p>
                                <p><strong>Subtotal:</strong> $<?php echo $quotation['subtotal']; ?></p>
                                <h6>Products:</h6>
                                <ul>
                                    <?php while ($product = $productQuery->fetch_assoc()) : ?>
                                        <li><?php echo $product['name']; ?> - <?php echo $product['quantity']; ?></li>
                                    <?php endwhile; ?>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Botón para nueva orden -->
</div>

<!-- Modal Nueva Orden -->
<div class="modal fade" id="newOrderModal" tabindex="-1" aria-labelledby="newOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="newOrder.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Purchase Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="requiredDate" class="form-label">Required Date</label>
                        <input type="date" class="form-control" id="requiredDate" name="requiredDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="requisitionId" class="form-label">Requisition</label>
                        <select class="form-select" id="requisitionId" name="requisitionId" required>
                            <?php
                            $requisitions = $conexion->query("SELECT id FROM requisition");
                            while ($req = $requisitions->fetch_assoc()) : ?>
                                <option value="<?php echo $req['id']; ?>"><?php echo $req['id']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>











<div class="modal fade" id="modifyModal" tabindex="-1" aria-labelledby="modifyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modifyModalLabel">Modify Purchase Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="purcahsemod/update_orders.php" method="POST">
                            <div class="mb-3">
                                <label for="orderSelect" class="form-label">Select Purchase Order</label>
                                <select class="form-select" id="orderSelect" name="orderId" required>
                                    <option value="">Seleccionar</option>
                                    <?php
                                    // Consultamos las órdenes de compra
                                    $query = "SELECT id FROM purchaseOrder";
                                    $result = mysqli_query($conexion, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='".$row['id']."'>Orden de Compra ".$row['id']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="requiredDate" class="form-label">Required Date</label>
                                <input type="date" class="form-control" id="requiredDate" name="requiredDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="requiredQuantity" class="form-label">Required Quantity</label>
                                <input type="number" class="form-control" id="requiredQuantity" name="requiredQuantity" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="total" class="form-label">Total</label>
                                <input type="text" class="form-control" id="total" name="total" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="subtotal" class="form-label">Subtotal</label>
                                <input type="text" class="form-control" id="subtotal" name="subtotal" disabled>
                            </div>
                            <button type="submit" class="btn btn-success">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Delete -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Purchase Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="purcahsemod/delete_order.php" method="POST">
                            <div class="mb-3">
                                <label for="deleteOrderSelect" class="form-label">Select Purchase Order</label>
                                <select class="form-select" id="deleteOrderSelect" name="orderId" required>
                                    <option value="">Seleccionar</option>
                                    <?php
                                    // Consultamos las órdenes de compra para eliminar
                                    $query = "SELECT id FROM purchaseOrder";
                                    $result = mysqli_query($conexion, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='".$row['id']."'>Orden de Compra ".$row['id']."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>










    <!-- Footer -->
    <footer class="text-center py-3 border-top">
        <small>&copy; 2024 Empresa. Todos los derechos reservados.</small>
    </footer>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Función para cargar los datos de la orden seleccionada en el modal de modificación
        document.getElementById('orderSelect').addEventListener('change', function () {
            const orderId = this.value;

            if (orderId) {
                fetchOrderDetails(orderId);
            }
        });

        function fetchOrderDetails(orderId) {
            fetch('purcahsemod/get_order_details.php?orderId=' + orderId)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('requiredDate').value = data.requiredDate;
                    document.getElementById('requiredQuantity').value = data.requiredQuantity;
                    document.getElementById('total').value = data.total;
                    document.getElementById('subtotal').value = data.subtotal;
                });
        }
    </script>

</body> 
</html>