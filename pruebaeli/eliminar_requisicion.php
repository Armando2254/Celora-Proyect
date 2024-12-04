<?php
include '../conexion_bd.php';

// Consulta para obtener las requisiciones
$query = "SELECT id, description FROM requisition";
$result = mysqli_query($conexion, $query);
$requisitions3 = [];
while ($row = mysqli_fetch_assoc($result)) {
    $requisitions3[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Requisición</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <button id="botton3" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal3">Eliminar</button>

    <!-- Modal para seleccionar requisición -->
    <div class="modal fade" id="modal3" tabindex="-1" aria-labelledby="modalLabel3" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel3">Eliminar Requisición</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formSelectRequisicion3">
                        <div class="mb-3">
                            <label for="selectRequisicion3" class="form-label">Selecciona una requisición:</label>
                            <select id="selectRequisicion3" class="form-select" required>
                                <option value="" disabled selected>Seleccione</option>
                                <?php foreach ($requisitions3 as $requisition): ?>
                                    <option value="<?= $requisition['id'] ?>"><?= $requisition['description'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btnDelete3">Borrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmModal3" tabindex="-1" aria-labelledby="confirmLabel3" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmLabel3">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Realmente desea eliminar esta requisición?</p>
                    <div id="requisicionDatos3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmDelete3">Sí</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const btnDelete = document.getElementById('btnDelete3');
    const btnConfirmDelete = document.getElementById('btnConfirmDelete3');
    const selectRequisicion = document.getElementById('selectRequisicion3');
    const requisicionDatos = document.getElementById('requisicionDatos3');

    btnDelete.addEventListener('click', () => {
        const requisitionId = selectRequisicion.value;

        if (!requisitionId) {
            alert('Seleccione una requisición');
            return;
        }

        // Solicitar datos de la requisición y productos
        fetch(`get_requisicion.php?id=${requisitionId}`)
            .then(response => response.json())
            .then(data => {
                requisicionDatos.innerHTML = `
                    <p><strong>ID:</strong> ${data.id}</p>
                    <p><strong>Descripción:</strong> ${data.description}</p>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.products.map(product => `
                                <tr>
                                    <td>${product.name}</td>
                                    <td>${product.quantity}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;
                const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal3'));
                confirmModal.show();
            });
    });

    btnConfirmDelete.addEventListener('click', () => {
        const requisitionId = selectRequisicion.value;

        fetch(`delete_requisicion.php?id=${requisitionId}`)
            .then(response => response.text())
            .then(result => {
                alert(result);
                location.reload();
            });
    });
</script>
</body>
</html>
