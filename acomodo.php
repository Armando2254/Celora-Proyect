<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modal con Bootstrap</title>
  <link rel="icon" href="images/logoCAB.png" type="images/png">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <!-- Botón que abre el modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#miModal">
      Abrir Modal
    </button>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="miModalLabel">Título del Modal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
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

    <button type="button" class="btn btn-primary" id="editProductsButton" data-bs-toggle="modal" data-bs-target="#editProductsModal">Editar Productos</button>



















        </div>


        
     
      </div>
    </div>
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






<div class="modal fade" id="editProductsModal" tabindex="-1" aria-labelledby="editProductsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductsModalLabel">Editar Productos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="products-form">
                        <div id="products-container">
                            <!-- Contenedor dinámico de productos -->
                        </div>
                        <button type="button" class="btn btn-success" id="addProductButton">Agregar Producto</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="saveChangesButton">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>


  <!-- Bootstrap JS y dependencias -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>





// Variables globales
const requisitionSelect = document.getElementById("requisitionSelect");
const modifyButton = document.getElementById("modifyButton");
const dateInput = document.getElementById("dateInput");
const descriptionInput = document.getElementById("descriptionInput");
const statusSelect = document.getElementById("statusSelect");
const requisitionId = document.getElementById("requisitionId");
const productsContainer = document.getElementById("products-container");
let currentRequisitionId = null;

// Función para cargar las requisiciones
async function loadRequisitions() {
    const response = await fetch("peuebas/fetch_requisitions.php");
    const data = await response.json();
    requisitionSelect.innerHTML = '<option value="">Selecciona una requisición</option>';
    data.forEach(req => {
        const option = document.createElement("option");
        option.value = req.id;
        option.textContent = `Requisición #${req.id} - ${req.description}`;
        requisitionSelect.appendChild(option);
    });
}

// Habilitar botón de modificar
requisitionSelect.addEventListener("change", () => {
    modifyButton.disabled = !requisitionSelect.value;
});

// Prellenar el modal con datos de la requisición seleccionada
modifyButton.addEventListener("click", async () => {
    const selectedId = requisitionSelect.value;
    if (!selectedId) {
        alert("Selecciona una requisición.");
        return;
    }

    try {
        const response = await fetch(`peuebas/fetch_requisitions.php?id=${selectedId}`);
        const data = await response.json();
        requisitionId.value = data.id;
        dateInput.value = data.date;
        descriptionInput.value = data.description;
        statusSelect.value = data.status;
        currentRequisitionId = selectedId;

        // Cargar los productos de la requisición en el modal
        const productResponse = await fetch(`pruebaProd/get_products.php?requisitionId=${selectedId}`);
        const products = await productResponse.json();
        productsContainer.innerHTML = "";
        products.forEach(product => {
            addProductRow(product.productCode, product.name, product.quantity);
        });
    } catch (error) {
        console.error("Error al cargar datos de la requisición:", error);
        alert("No se pudieron cargar los datos.");
    }
});






editProductsButton.addEventListener("click", async () => {
    const selectedId = requisitionSelect.value;
    if (!selectedId) {
        alert("Selecciona una requisición.");
        return;
    }

    try {
        const response = await fetch(`peuebas/fetch_requisitions.php?id=${selectedId}`);
        const data = await response.json();
        requisitionId.value = data.id;
        dateInput.value = data.date;
        descriptionInput.value = data.description;
        statusSelect.value = data.status;
        currentRequisitionId = selectedId;

        // Cargar los productos de la requisición en el modal
        const productResponse = await fetch(`pruebaProd/get_products.php?requisitionId=${selectedId}`);
        const products = await productResponse.json();
        productsContainer.innerHTML = "";
        products.forEach(product => {
            addProductRow(product.productCode, product.name, product.quantity);
        });
    } catch (error) {
        console.error("Error al cargar datos de la requisición:", error);
        alert("No se pudieron cargar los datos.");
    }
});








// Actualizar datos de la requisición
document.getElementById("editForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData();
    formData.append('id', requisitionId.value);
    formData.append('date', dateInput.value);
    formData.append('description', descriptionInput.value);
    formData.append('status', statusSelect.value);

    try {
        const response = await fetch('peuebas/update_requisition.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.success) {
            alert("Requisición actualizada exitosamente.");
            location.reload();
        } else {
            alert("Error al actualizar la requisición.");
        }
    } catch (error) {
        console.error("Error al actualizar:", error);
    }
});

// Función para agregar filas dinámicas de productos
function addProductRow(productCode = "", productName = "", quantity = 1) {
    const row = document.createElement("div");
    row.className = "row mb-3 product-row";

    row.innerHTML = `
        <div class="col-md-6">
            <label class="form-label">Producto</label>
            <select class="form-select product-select" required>
                <option value="${productCode}" selected>${productName || "Selecciona un producto"}</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Cantidad</label>
            <input type="number" class="form-control product-quantity" min="1" value="${quantity}" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-remove">Eliminar</button>
        </div>
    `;

    row.querySelector(".btn-remove").addEventListener("click", () => row.remove());
    productsContainer.appendChild(row);
    loadProducts(row.querySelector(".product-select"), productCode);
}

// Cargar productos en un select
async function loadProducts(selectElement, selectedCode = "") {
    const response = await fetch("pruebaProd/get_all_products.php");
    const data = await response.json();

    selectElement.innerHTML = '<option value="">Selecciona un producto</option>';
    data.forEach(product => {
        const option = document.createElement("option");
        option.value = product.code;
        option.textContent = product.name;
        if (product.code === selectedCode) option.selected = true;
        selectElement.appendChild(option);
    });
}

// Guardar cambios en productos
document.getElementById("saveChangesButton").addEventListener("click", async () => {
    const rows = document.querySelectorAll(".product-row");
    const products = Array.from(rows).map(row => ({
        productCode: row.querySelector(".product-select").value,
        quantity: row.querySelector(".product-quantity").value
    }));

    try {
        const response = await fetch("pruebaProd/save_changes.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ requisitionId: currentRequisitionId, products })
        });

        const result = await response.json();
        if (result.success) {
            alert("Cambios guardados correctamente.");
            location.reload();
        } else {
            alert("Error al guardar cambios.");
        }
    } catch (error) {
        console.error("Error al guardar cambios:", error);
    }
});

// Agregar nueva fila de producto
document.getElementById("addProductButton").addEventListener("click", () => addProductRow());

// Cerrar modal de edición
const closeModalButtons = document.querySelectorAll('[data-close-modal]');
const modal = document.getElementById("editModal");
closeModalButtons.forEach(button => {
    button.addEventListener("click", () => {
        modal.style.display = "none";
    });
});
window.addEventListener("click", (event) => {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});

// Inicializar carga de requisiciones
loadRequisitions();









  </script>
</body>
</html>
