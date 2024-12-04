<?php
include('../conexion_bd.php'); // Asegúrate de tener el archivo de conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Órdenes de Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Gestión de Órdenes de Compra</h2>
        <div class="mt-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modifyModal">Modify</button>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
        </div>

        <!-- Modal Modify -->
        <div class="modal fade" id="modifyModal" tabindex="-1" aria-labelledby="modifyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modifyModalLabel">Modificar Orden de Compra</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="update_order.php" method="POST">
                            <div class="mb-3">
                                <label for="orderSelect" class="form-label">Seleccione Orden de Compra</label>
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
                                <label for="requiredDate" class="form-label">Fecha Requerida</label>
                                <input type="date" class="form-control" id="requiredDate" name="requiredDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="requiredQuantity" class="form-label">Cantidad Requerida</label>
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
                            <button type="submit" class="btn btn-success">Actualizar</button>
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
                        <h5 class="modal-title" id="deleteModalLabel">Eliminar Orden de Compra</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="delete_order.php" method="POST">
                            <div class="mb-3">
                                <label for="deleteOrderSelect" class="form-label">Seleccione Orden de Compra</label>
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
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
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
            fetch('get_order_details.php?orderId=' + orderId)
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
