<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cotizaciones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#quotationModal">Abrir Cotizaciones</button>
    </div>

    <!-- Modal de Cotizaciones -->
    <div class="modal fade" id="quotationModal" tabindex="-1" aria-labelledby="quotationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quotationModalLabel">Seleccionar Cotización</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="quotationForm">
                        <div class="mb-3">
                            <label for="quotationSelect" class="form-label">Cotización</label>
                            <select id="quotationSelect" class="form-select">
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
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Realmente quieres eliminar esta cotización?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Sí</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        // Cargar las cotizaciones en el select
        $(document).ready(function() {
            $.ajax({
                url: 'load_quotations.php',
                method: 'GET',
                success: function(data) {
                    const quotations = JSON.parse(data);
                    const quotationSelect = $('#quotationSelect');
                    quotationSelect.empty();
                    quotations.forEach(quotation => {
                        quotationSelect.append(new Option(quotation.id, quotation.id));
                    });
                },
                error: function(err) {
                    alert('Error al cargar las cotizaciones.');
                }
            });

            // Confirmar eliminación
            $('#confirmDeleteButton').click(function() {
                const quotationId = $('#quotationSelect').val();
                if (quotationId) {
                    $.ajax({
                        url: 'delete_quotation.php',
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
    </script>
</body>
</html>
