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
    fetch(`pruebaeli/get_requisicion.php?id=${requisitionId}`)
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

    fetch(`pruebaeli/delete_requisicion.php?id=${requisitionId}`)
        .then(response => response.text())
        .then(result => {
            alert(result);
            location.reload();
        });
});