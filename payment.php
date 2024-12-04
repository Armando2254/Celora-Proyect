
<?php
session_start();

if (empty($_SESSION["employeeNumber"])) {
    header("Location: login.php");
}


include('conexion_bd.php');

// Fetch payment data
$paymentsQuery = "SELECT id, reference FROM payment";
$paymentsResult = mysqli_query($conexion, $paymentsQuery);

// Fetch payment types
$paymentTypesQuery = "SELECT code, type FROM paymentType";
$paymentTypesResult = mysqli_query($conexion, $paymentTypesQuery);

// Fetch purchase orders
$purchaseOrdersQuery = "SELECT id FROM purchaseOrder";
$purchaseOrdersResult = mysqli_query($conexion, $purchaseOrdersQuery);

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Título de tu página</title>
    <link href="css/btn.css" rel="stylesheet" >

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="images/logoCAB.png" type="images/png">
    <link href="css/estilosInicio.css" rel="stylesheet" >
    <link href="css/btn.css" rel="stylesheet" >

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
  <a href="controladorCS.php" class="btn btn-outline-danger">Salir</a>
</form>
  </div>
</nav>



<div class="container mt-5">
        <!-- Botón Inicio alineado a la izquierda -->
        <a href="inicio.php" class="btn btn-custom">Inicio</a>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modifyModal">Modify</button>
        <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Delete</button>
      
    </div>





<div class="container my-4">
    <h1 class="text-center">Payments</h1>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPaymentModal">Add Payment</button>

    <div class="row">
        <?php
        include 'conexion_bd.php';
        $query = "SELECT * FROM payment ORDER BY id DESC";
        $result = $conexion->query($query);

        while ($row = $result->fetch_assoc()) {
            echo '
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Payment #' . $row['id'] . '</h5>
                        <p class="card-text">Reference: ' . $row['reference'] . '</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal' . $row['id'] . '">
                            Ver Detalles
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="paymentModal' . $row['id'] . '" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">Payment Details #' . $row['id'] . '</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">';
                            $orderQuery = "SELECT * FROM purchaseOrder WHERE id = " . $row['purchaseOrderId'];
                            $orderResult = $conexion->query($orderQuery);
                            $order = $orderResult->fetch_assoc();

                            if ($order) {
                                echo '
                                <p><strong>Purchase Order:</strong> #' . $order['id'] . '</p>
                                <p><strong>Required Quantity:</strong> ' . $order['requiredQuantity'] . '</p>
                                <p><strong>Total:</strong> $' . $order['total'] . '</p>
                                <p><strong>Subtotal:</strong> $' . $order['subtotal'] . '</p>';
                            } else {
                                echo '<p>NNo Information.</p>';
                            }
            echo '      </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>
</div>

<!-- Modal para agregar nuevo pago -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="add_payment.php" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPaymentModalLabel">Agregar Nuevo Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reference" class="form-label">Referencia</label>
                        <input type="text" class="form-control" name="reference" required>
                    </div>
                    <div class="mb-3">
                        <label for="concept" class="form-label">Concepto</label>
                        <input type="text" class="form-control" name="concept" required>
                    </div>
                    <div class="mb-3">
                        <label for="paymentType" class="form-label">Tipo de Pago</label>
                        <input type="text" class="form-control" name="paymentType" required>
                    </div>
                    <div class="mb-3">
                        <label for="purchaseOrder" class="form-label">Orden de Compra</label>
                        <select class="form-select" name="purchaseOrderId" required>
                            <?php
                            $ordersQuery = "SELECT * FROM purchaseOrder";
                            $ordersResult = $conexion->query($ordersQuery);

                            while ($order = $ordersResult->fetch_assoc()) {
                                echo '<option value="' . $order['id'] . '">#' . $order['id'] . ' - Total: $' . $order['total'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>




   <!-- Footer -->
   <footer class="text-center py-3 border-top">
        <small>&copy; 2024 Empresa. Todos los derechos reservados.</small>
    </footer>





    <div class="modal fade" id="modifyModal" tabindex="-1" role="dialog" aria-labelledby="modifyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifyModalLabel">Modificar Pago</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="paymod/update_payment.php" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="paymentSelect">Selecciona un Pago</label>
                        <select class="form-control" id="paymentSelect" name="paymentId" required>
                            <?php while ($payment = mysqli_fetch_assoc($paymentsResult)): ?>
                                <option value="<?= $payment['id'] ?>"><?= $payment['reference'] ?> (ID: <?= $payment['id'] ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="newReference">Nueva Referencia</label>
                        <input type="text" class="form-control" id="newReference" name="newReference" required>
                    </div>

                    <div class="form-group">
                        <label for="newConcept">Nuevo Concepto</label>
                        <input type="text" class="form-control" id="newConcept" name="newConcept" required>
                    </div>

                    <div class="form-group">
                        <label for="paymentType">Tipo de Pago</label>
                        <select class="form-control" id="paymentType" name="paymentType" required>
                            <?php while ($type = mysqli_fetch_assoc($paymentTypesResult)): ?>
                                <option value="<?= $type['code'] ?>"><?= $type['type'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="purchaseOrder">Orden de Compra</label>
                        <select class="form-control" id="purchaseOrder" name="purchaseOrder" required>
                            <?php while ($order = mysqli_fetch_assoc($purchaseOrdersResult)): ?>
                                <option value="<?= $order['id'] ?>">Orden #<?= $order['id'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Eliminar Pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="paymod/delete_payment.php" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="deletePaymentSelect">Selecciona un Pago</label>
                        <select class="form-control" id="deletePaymentSelect" name="paymentId" required>
                            <?php mysqli_data_seek($paymentsResult, 0); // Reset result set ?>
                            <?php while ($payment = mysqli_fetch_assoc($paymentsResult)): ?>
                                <option value="<?= $payment['id'] ?>"><?= $payment['reference'] ?> (ID: <?= $payment['id'] ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <p>¿Estás seguro de que deseas eliminar este pago?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>

<!-- AJAX para cargar los datos del pago -->
<script>
    $(document).ready(function() {
        // Detectar el cambio en el select de pagos
        $('#paymentSelect').change(function() {
            var paymentId = $(this).val();
            
            // Verificar que el ID sea válido
            if (paymentId) {
                $.ajax({
                    url: 'paymod/get_payment_data.php',
                    type: 'POST',
                    data: { id: paymentId },
                    success: function(response) {
                        console.log(response);  // Depuración: ver los datos recibidos
                        try {
                            var paymentData = JSON.parse(response);
                            // Asegurarse que los datos existan antes de asignar valores
                            if (paymentData.reference) {
                                $('#newReference').val(paymentData.reference);
                            }
                            if (paymentData.concept) {
                                $('#newConcept').val(paymentData.concept);
                            }
                            if (paymentData.paymentTypeCode) {
                                $('#paymentType').val(paymentData.paymentTypeCode);
                            }
                            if (paymentData.purchaseOrderId) {
                                $('#purchaseOrder').val(paymentData.purchaseOrderId);
                            }
                        } catch (e) {
                            console.error("Error al procesar los datos:", e);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error de AJAX:", status, error);
                    }
                });
            }
        });
    });
</script>

</body> 
</html>
