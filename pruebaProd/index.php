<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisiciones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Gestión de Requisiciones</h1>
        <!-- Formulario principal -->
        <form id="requisition-form">
            <div class="mb-3">
                <label for="requisitionSelect" class="form-label">Selecciona una Requisición</label>
                <select id="requisitionSelect" class="form-select" required>
                    <option value="">Cargando...</option>
                </select>
            </div>
            <button type="button" class="btn btn-primary" id="editProductsButton">Editar Productos</button>
        </form>
    </div>

    <!-- Modal para editar productos -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const requisitionSelect = document.getElementById("requisitionSelect");
        const productsContainer = document.getElementById("products-container");
        let requisitionId = null;

        // Cargar requisiciones
        async function loadRequisitions() {
            const response = await fetch("get_requisitions.php");
            const data = await response.json();
            requisitionSelect.innerHTML = '<option value="">Selecciona una requisición</option>';
            data.forEach(req => {
                const option = document.createElement("option");
                option.value = req.id;
                option.textContent = `Requisición #${req.id} - ${req.description}`;
                requisitionSelect.appendChild(option);
            });
        }

        // Abrir modal para editar productos
        document.getElementById("editProductsButton").addEventListener("click", async () => {
            requisitionId = requisitionSelect.value; // Obtener el ID de la requisición seleccionada
    if (!requisitionId) {
        alert("Selecciona una requisición."); // Validación si no hay requisición seleccionada
        return;
    }

    try {
        // Realizar la consulta de productos de la requisición
        const response = await fetch(`get_products.php?requisitionId=${requisitionId}`);
        const data = await response.json();

        // Limpiar el contenedor de productos y agregar filas dinámicas
        productsContainer.innerHTML = "";
        data.forEach(product => {
            addProductRow(product.productCode, product.name, product.quantity);
        });

        // Mostrar el modal de edición
        new bootstrap.Modal(document.getElementById("editProductsModal")).show();
    } catch (error) {
        console.error("Error al cargar productos:", error);
        alert("No se pudieron cargar los productos.");
    }
});

        // Agregar fila de producto
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
            const response = await fetch("get_all_products.php");
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

        // Guardar cambios
        document.getElementById("saveChangesButton").addEventListener("click", async () => {
            const rows = document.querySelectorAll(".product-row");
            const products = Array.from(rows).map(row => ({
                productCode: row.querySelector(".product-select").value,
                quantity: row.querySelector(".product-quantity").value
            }));

            const response = await fetch("save_changes.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ requisitionId, products })
            });

            const result = await response.json();
            if (result.success) {
                alert("Cambios guardados correctamente.");
                new bootstrap.Modal(document.getElementById("editProductsModal")).hide();
            } else {
                alert("Error al guardar cambios.");
            }
        });

        // Agregar producto
        document.getElementById("addProductButton").addEventListener("click", () => addProductRow());

        loadRequisitions();







        async function saveChanges() {
    const rows = document.querySelectorAll(".product-row");
    const updates = [];
    const requisitionId = document.getElementById("requisitionSelect").value;

    rows.forEach(row => {
        const productCode = row.querySelector(".product-select").value;
        const quantity = row.querySelector(".product-quantity").value;

        if (quantity <= 0) {
            alert("La cantidad debe ser mayor a cero.");
            return;
        }

        updates.push({
            productCode,
            quantity
        });
    });

    try {
        const response = await fetch("update_products.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ requisitionId, updates })
        });

        const result = await response.json();

        if (result.success) {
            alert("Cambios guardados exitosamente.");
            location.reload(); // Opcional, para recargar la página
        } else {
            alert("Error al guardar cambios: " + result.message);
        }
    } catch (error) {
        console.error("Error en la solicitud:", error);
        alert("No se pudieron guardar los cambios.");
    }
}







// Selecciona el botón de cerrar
const closeModalButtons = document.querySelectorAll('[data-close-modal]');
const modal = document.getElementById("editModal");

// Añade eventos de cierre a cada botón
closeModalButtons.forEach(button => {
    button.addEventListener("click", () => {
        modal.style.display = "none";
    });
});

// Opcional: También cerrar cuando se hace clic fuera del modal
window.addEventListener("click", (event) => {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});



    </script>
</body>
</html>
