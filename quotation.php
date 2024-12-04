<?php


session_start();

if (empty($_SESSION["employeeNumber"])) {
    header("Location: login.php");
}



include 'conexion_bd.php';

// Obtener cotizaciones
$quotationsQuery = "SELECT * FROM quotation";
$quotationsResult = $conexion->query($quotationsQuery);

// Obtener proveedores
$suppliersQuery = "SELECT id, legalName FROM supplier";
$suppliersResult = $conexion->query($suppliersQuery);

// Obtener requisiciones
$requisitionsQuery = "SELECT requisition_id, description FROM requisiciones_sin_cotizacion";
$requisitionsResult = $conexion->query($requisitionsQuery);
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

        .jj{
            background-color: #50C878;
            border-color: none;
            border-style: none;
        }
    </style>
     <style>
        /* Estilo personalizado para el botón */
        .btn-custom {
            background-color: #50C878;
            color: white; /* Texto blanco para contraste */
        }

        .btn-custom:hover {
            background-color: #45b568; /* Color ligeramente más oscuro al pasar el mouse */
        }

        /* Asegúrate de que el modal se apile sobre otros elementos */
.modal-backdrop {
    z-index: 1040 !important; /* Esto asegura que el fondo del modal tenga un alto z-index */
}

.modal {
    z-index: 1050 !important; /* Esto asegura que el modal tenga el mayor z-index */
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



        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#selectQuotationModal">Modify</button>

        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#quotationModal">Delete</button>



      
    </div>

    <div class="modal fade" id="selectQuotationModal" tabindex="-1" aria-labelledby="selectQuotationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectQuotationModalLabel">Select Quotation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="selectQuotationForm">
                        <div class="mb-3">
                            <label for="quotationSelect" class="form-label">Quotations</label>
                            <select id="quotationSelect" class="form-select">
                                <!-- Opciones cargadas dinámicamente -->
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary" id="modifyButton" data-bs-toggle="modal" data-bs-target="#modifyQuotationModal">
                            Seleccionar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Modificar Cotización -->
    <div class="modal fade" id="modifyQuotationModal" tabindex="-1" aria-labelledby="modifyQuotationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modifyQuotationModalLabel">Modify Quotation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="modifyQuotationForm">
                        <div class="mb-3">
                            <label for="issueDate" class="form-label">Issue date</label>
                            <input type="date" id="issueDate" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="total" class="form-label">Total</label>
                            <input type="number" id="total" class="form-control" step="0.01">
                        </div>
                        <div class="mb-3">
                            <label for="subtotal" class="form-label">Subtotal</label>
                            <input type="number" id="subtotal" class="form-control" step="0.01">
                        </div>
                        <div class="mb-3">
                            <label for="supplierSelect" class="form-label">Supplier</label>
                            <select id="supplierSelect" class="form-select">
                                <!-- Opciones cargadas dinámicamente -->
                            </select>
                        </div>
                        <button type="button" class="btn btn-success" id="updateButton">Aceptt</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="quotationModal" tabindex="-1" aria-labelledby="quotationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quotationModalLabel">Select Quotation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="quotationForm">
                        <div class="mb-3">
                            <label for="quotationSelect" class="form-label">Quotations</label>
                            <select id="quotationSelect2" class="form-select">
                                <!-- Opciones cargadas dinámicamente -->
                            </select>
                        </div>
                        <button type="button" class="btn btn-danger" id="deleteButton" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm delete?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Are you sure you want to delete this quotation?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Yes</button>
                </div>
            </div>
        </div>
    </div>



<div class="container mt-5">
    <h1 class="mb-4">Quotations</h1>

    <!-- Botón para agregar nueva cotización -->
    <button class="btn mb-4" data-bs-toggle="modal" data-bs-target="#addQuotationModal" style="background-color: #50C878;">Add Quotation</button>

    <!-- Mostrar cotizaciones como tarjetas -->
    <div class="row">
        <?php while ($row = $quotationsResult->fetch_assoc()) { ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Quotation #<?php echo $row['id']; ?></h5>
                        <p>Date: <?php echo $row['issueDate']; ?></p>
                        <p>Total: $<?php echo $row['total']; ?></p>
                        <button class="btn btn-info jj"  data-bs-toggle="modal" 
                                data-bs-target="#quotationModal<?php echo $row['id']; ?>">View details</button>
                    </div>
                </div>
            </div>

            <!-- Modal de detalles de la cotización -->
            <div class="modal fade" id="quotationModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Quotation details #<?php echo $row['id']; ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Aquí puedes cargar los detalles asociados desde PHP -->
                            <p><strong>Subtotal:</strong> $<?php echo $row['subtotal']; ?></p>
                            <p><strong>Supplier ID:</strong> <?php echo $row['supplierId']; ?></p>
                            <!-- Productos de la requisición asociada -->
                            <h5>Products:</h5>
                            <ul>
                                <?php
                                $productsQuery = "SELECT p.name, pr.quantity FROM product p 
                                    JOIN product_requisition pr ON p.code = pr.productCode
                                    JOIN requisition r ON pr.requisitionId = r.id
                                    WHERE r.id IN (SELECT requisitionId FROM report WHERE quotationId = '{$row['id']}')";
                                $productsResult = $conexion->query($productsQuery);
                                while ($product = $productsResult->fetch_assoc()) {
                                    echo "<li>{$product['name']} - Cantidad: {$product['quantity']}</li>";
                                }
                                ?>
                                
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" data-bs-dismiss="modal" style="background-color: #50C878;">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>







<!-- Modal para agregar nueva cotización -->
<div class="modal fade" id="addQuotationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="add_quotation.php" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Quotation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                <div class="mb-3">
                        <label for="id" class="form-label">ID</label>
                        <input type="text" name="id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="issueDate" class="form-label">Issue Date</label>
                        <input type="date" name="issueDate" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="total" class="form-label">Total</label>
                        <input type="number" step="0.01" name="total" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="subtotal" class="form-label">Subtotal</label>
                        <input type="number" step="0.01" name="subtotal" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="supplierId" class="form-label">Supplier</label>
                        <select name="supplierId" class="form-select" required>
                            <?php while ($supplier = $suppliersResult->fetch_assoc()) { ?>
                                <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['legalName']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="requisitionId" class="form-label">Requisition</label>
                        <select name="requisitionId" class="form-select" required>
                            <?php while ($requisition = $requisitionsResult->fetch_assoc()) { ?>
                                <option value="<?php echo $requisition['requisition_id']; ?>"><?php echo $requisition['description']; ?></option>
                            <?php } ?>
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
    <!-- Footer -->
    <footer class="text-center py-3 border-top">
        <small>&copy; 2024 Empresa. Todos los derechos reservados.</small>
    </footer>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


<script>



    // Función para actualizar el estado de la requisición
    function updateStatus(id, status) {
        fetch('update_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Estado actualizado correctamente.');
                location.reload(); // Recargar la página para reflejar cambios
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }




    $(document).ready(function() {
    // Cargar cotizaciones en el primer select
    $.ajax({
        url: 'eliquo/load_quotations.php',
        method: 'GET',
        success: function(data) {
            const quotations = JSON.parse(data);
            const quotationSelect = $('#quotationSelect');
            quotationSelect.empty();
            quotations.forEach(quotation => {
                quotationSelect.append(new Option(quotation.id, quotation.id));
            });
        },
        error: function() {
            alert('Error al cargar las cotizaciones.');
        }
    });

    // Cargar proveedores en el segundo modal
    $.ajax({
        url: 'modifquo/load_suppliers.php',
        method: 'GET',
        success: function(data) {
            const suppliers = JSON.parse(data);
            const supplierSelect = $('#supplierSelect');
            supplierSelect.empty();
            suppliers.forEach(supplier => {
                supplierSelect.append(new Option(supplier.legalName, supplier.id));
            });
        },
        error: function() {
            alert('Error al cargar los proveedores.');
        }
    });

    // Mostrar datos de cotización seleccionada
    $('#modifyButton').click(function() {
        const quotationId = $('#quotationSelect').val();
        $.ajax({
            url: 'modifquo/get_quotation.php',
            method: 'GET',
            data: { id: quotationId },
            success: function(data) {
                const quotation = JSON.parse(data);
                $('#issueDate').val(quotation.issueDate);
                $('#total').val(quotation.total);
                $('#subtotal').val(quotation.subtotal);
                $('#supplierSelect').val(quotation.supplierId);
            },
            error: function() {
                alert('Error al cargar los datos de la cotización.');
            }
        });
    });

    // Actualizar datos de cotización
    $('#updateButton').click(function() {
        const quotationId = $('#quotationSelect').val();
        const updatedData = {
            id: quotationId,
            issueDate: $('#issueDate').val(),
            total: $('#total').val(),
            subtotal: $('#subtotal').val(),
            supplierId: $('#supplierSelect').val()
        };

        $.ajax({
            url: 'modifquo/update_quotation.php',
            method: 'POST',
            data: updatedData,
            success: function(response) {
                alert(response);
                $('#modifyQuotationModal').modal('hide');
            },
            error: function() {
                alert('Error al actualizar la cotización.');
            }
        });
    });
});





$(document).ready(function() {
            $.ajax({
                url: 'eliquo/load_quotations.php',
                method: 'GET',
                success: function(data) {
                    const quotations = JSON.parse(data);
                    const quotationSelect2 = $('#quotationSelect2');
                    quotationSelect2.empty();
                    quotations.forEach(quotation => {
                        quotationSelect2.append(new Option(quotation.id, quotation.id));
                    });
                },
                error: function(err) {
                    alert('Error al cargar las cotizaciones.');
                }
            });

            // Confirmar eliminación
            $('#confirmDeleteButton').click(function() {
                const quotationId = $('#quotationSelect2').val();
                if (quotationId) {
                    $.ajax({
                        url: 'eliquo/delete_quotation.php',
                        method: 'POST',
                        data: { id: quotationId },
                        success: function(response) {
                            alert(response);
                            $('#confirmDeleteModal').modal('hide');
                            $('#quotationModal').modal('hide');
                            location.reload();
                        },
                        error: function(err) {
                            alert('Error al eliminar la cotización.');
                        }
                    });
                }
            });
        });
























document.addEventListener('hidden.bs.modal', function (event) {
  const modals = document.querySelectorAll('.modal.show'); // Obtener todos los modales abiertos
  if (modals.length === 0) { // Si no hay modales abiertos
    document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
      backdrop.remove(); // Eliminar el fondo solo si no quedan modales abiertos
    });
  }
});

   



</script>

</body> 
</html>


