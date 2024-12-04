<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Cotización</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#selectQuotationModal">Modificar Cotización</button>
    </div>

    <!-- Modal para Seleccionar Cotización -->
    <div class="modal fade" id="selectQuotationModal" tabindex="-1" aria-labelledby="selectQuotationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectQuotationModalLabel">Seleccionar Cotización</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="selectQuotationForm">
                        <div class="mb-3">
                            <label for="quotationSelect" class="form-label">Cotización</label>
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
                    <h5 class="modal-title" id="modifyQuotationModalLabel">Modificar Cotización</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="modifyQuotationForm">
                        <div class="mb-3">
                            <label for="issueDate" class="form-label">Fecha de Emisión</label>
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
                            <label for="supplierSelect" class="form-label">Proveedor</label>
                            <select id="supplierSelect" class="form-select">
                                <!-- Opciones cargadas dinámicamente -->
                            </select>
                        </div>
                        <button type="button" class="btn btn-success" id="updateButton">Aceptar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../modifquo/quodif.js"></script>

</body>
</html>
