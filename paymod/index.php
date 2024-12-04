<?php
include('../conexion_bd.php');

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
    <title>Modificar y Eliminar Pagos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Administrar Pagos</h2>
    <div class="row">
        <button class="btn btn-primary" data-toggle="modal" data-target="#modifyModal">Modificar Pago</button>
        <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Eliminar Pago</button>
    </div>
</div>

<!-- Modify Modal -->
<div class="modal fade" id="modifyModal" tabindex="-1" role="dialog" aria-labelledby="modifyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifyModalLabel">Modificar Pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="update_payment.php" method="POST">
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
            <form action="delete_payment.php" method="POST">
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

<!-- Bootstrap JS and dependencies -->
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
                    url: 'get_payment_data.php',
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
