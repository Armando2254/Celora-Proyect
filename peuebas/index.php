<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Requisiciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Requisiciones</h1>

    <!-- Select para mostrar las requisiciones -->
    <label for="requisitionSelect" class="form-label">Selecciona una requisición:</label>
    <select id="requisitionSelect" class="form-select mb-3">
        <option value="">Cargando...</option>
    </select>

    <!-- Botón para abrir el modal -->
    <button id="modifyButton" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal" disabled>
        Modificar
    </button>
</div>

<!-- Modal para modificar requisición -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Modificar Requisición</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="requisitionId">
                    <div class="mb-3">
                        <label for="dateInput" class="form-label">Fecha:</label>
                        <input type="date" id="dateInput" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="descriptionInput" class="form-label">Descripción:</label>
                        <textarea id="descriptionInput" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="statusSelect" class="form-label">Estatus:</label>
                        <select id="statusSelect" class="form-select" required>
                            <option value="Pending">Pending</option>
                            <option value="Complete">Complete</option>
                            <option value="Accept">Accept</option>
                            <option value="Reject">Reject</option>
                            <option value="In process">In process</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const requisitionSelect = document.getElementById("requisitionSelect");
    const modifyButton = document.getElementById("modifyButton");
    const dateInput = document.getElementById("dateInput");
    const descriptionInput = document.getElementById("descriptionInput");
    const statusSelect = document.getElementById("statusSelect");
    const requisitionId = document.getElementById("requisitionId");

    // Cargar requisiciones desde la base de datos
    fetch('fetch_requisitions.php')
        .then(response => response.json())
        .then(data => {
            requisitionSelect.innerHTML = '<option value="">Selecciona una requisición</option>';
            data.forEach(req => {
                const option = document.createElement("option");
                option.value = req.id;
                option.textContent = `ID: ${req.id} - ${req.description}`;
                requisitionSelect.appendChild(option);
            });
        });

    // Habilitar botón modificar cuando se selecciona una requisición
    requisitionSelect.addEventListener("change", () => {
        modifyButton.disabled = !requisitionSelect.value;
    });

    // Prellenar modal con los datos de la requisición seleccionada
    modifyButton.addEventListener("click", () => {
        const selectedId = requisitionSelect.value;
        fetch(`fetch_requisitions.php?id=${selectedId}`)
            .then(response => response.json())
            .then(data => {
                requisitionId.value = data.id;
                dateInput.value = data.date;
                descriptionInput.value = data.description;
                statusSelect.value = data.status;
            });
    });

    // Actualizar requisición
    document.getElementById("editForm").addEventListener("submit", (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('id', requisitionId.value);
        formData.append('date', dateInput.value);
        formData.append('description', descriptionInput.value);
        formData.append('status', statusSelect.value);

        fetch('update_requisition.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Requisición actualizada exitosamente");
                    location.reload();
                } else {
                    alert("Error al actualizar la requisición");
                }
            });
    });












    
</script>
</body>
</html>
