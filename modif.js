

// Variables globales
const requisitionSelect = document.getElementById("requisitionSelect");
const modifyButton = document.getElementById("modifyButton");
const editProductsButton = document.getElementById("editProductsButton");
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




// Inicializar carga de requisiciones
loadRequisitions();




document.addEventListener('hidden.bs.modal', function () {
  document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
      backdrop.remove();
  });
});

